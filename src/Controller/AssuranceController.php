<?php

namespace App\Controller;

use App\Entity\Assurance;
use App\Form\AssuranceType;
use App\Repository\AssuranceRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/assurance")
 */
class AssuranceController extends AbstractController
{
	private $_em;
	/**
	 * @var AssuranceRepository
	 */
	private $assuranceRepository;
	
	public function __construct(EntityManagerInterface $_em, AssuranceRepository $assuranceRepository)
	{
		$this->_em = $_em;
		$this->assuranceRepository = $assuranceRepository;
	}
	
    /**
     * @Route("/", name="assurance_index", methods={"GET","POST"})
     */
    public function index(Request $request, AssuranceRepository $assuranceRepository): Response
    {
	    $assurance = new Assurance();
	    $form = $this->createForm(AssuranceType::class, $assurance);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
			
			// Vérification de l'existence de l'assurance dans le système.
			if ($this->verification($assurance->getNom())){
				$this->addFlash('danger', "Erreur, cette assurance a déjà été enregistrée!");
				return $this->redirectToRoute('assurance_index', [], Response::HTTP_SEE_OTHER);
			}
			
			$slugify = new Slugify();
			$assurance->setSlug($slugify->slugify($assurance->getNom()));
			$assurance->setNom(strtoupper($assurance->getNom()));
		    $this->_em->persist($assurance);
		    $this->_em->flush();
			
			$this->addFlash('success', "L'assurance ".$assurance->getNom()." a été ajoutée avec succès!");
		
		    return $this->redirectToRoute('assurance_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->renderForm('assurance/index.html.twig', [
            'assurances' => $assuranceRepository->findBy([],['nom'=>"ASC"]),
	        'assurance' => $assurance,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="assurance_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $assurance = new Assurance();
        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($assurance);
            $entityManager->flush();

            return $this->redirectToRoute('assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('assurance/new.html.twig', [
            'assurance' => $assurance,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="assurance_show", methods={"GET"})
     */
    public function show(Assurance $assurance): Response
    {
        return $this->render('assurance/show.html.twig', [
            'assurance' => $assurance,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="assurance_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Assurance $assurance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        $slugify = new Slugify();
	        $assurance->setSlug($slugify->slugify($assurance->getNom()));
	        $assurance->setNom(strtoupper($assurance->getNom()));
            $entityManager->flush();
			
			$this->addFlash('success', "L'assurance ".$assurance->getNom()." a été modifiée avec succès!");

            return $this->redirectToRoute('assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('assurance/edit.html.twig', [
            'assurance' => $assurance,
            'form' => $form,
	        'assurances' => $this->assuranceRepository->findBy([],['nom'=>"ASC"])
        ]);
    }

    /**
     * @Route("/{id}", name="assurance_delete", methods={"POST"})
     */
    public function delete(Request $request, Assurance $assurance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assurance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($assurance);
            $entityManager->flush();
			
			$this->addFlash('success', "L'assurance ".$assurance->getNom()." a bien été supprimée!");
        }

        return $this->redirectToRoute('assurance_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/*
	 * Verification de l'existence l'assurance
	 */
	protected function verification($nom): bool
	{
		$verif = $this->assuranceRepository->findOneBy(['nom'=>$nom]);
		if ($verif) return true;
		else return false;
	}
}

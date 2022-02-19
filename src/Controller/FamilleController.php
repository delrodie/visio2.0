<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/famille")
 */
class FamilleController extends AbstractController
{
	private $em;
	private $familleRepository;
	
	public function __construct(EntityManagerInterface $em, FamilleRepository $familleRepository)
	{
		$this->em = $em;
		$this->familleRepository = $familleRepository;
	}
	
    /**
     * @Route("/", name="famille_index", methods={"GET", "POST"})
     */
    public function index(Request $request, FamilleRepository $familleRepository): Response
    {
	    $famille = new Famille();
	    $form = $this->createForm(FamilleType::class, $famille);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
			
			$nom = strtoupper($famille->getLibelle());
			
			// verification de l'existence de la famille
		    if ($this->verificationExistence($nom)){
				$this->addFlash('danger', "La famille ".$famille->getLibelle()." existe déjà dans le système");
			    return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
		    }
		
		    $slugify = new Slugify();
		    $famille->setSlug($slugify->slugify($famille->getLibelle()));
		    $famille->setLibelle($nom);
			
		    $this->em->persist($famille);
		    $this->em->flush();
			
			$this->addFlash('success', "La famille ".$famille->getLibelle()." a bien été ajoutée");
		
		    return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->renderForm('famille/index.html.twig', [
            'familles' => $familleRepository->findAll(),
	        'famille' => $famille,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="famille_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $famille = new Famille();
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($famille);
            $entityManager->flush();

            return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('famille/new.html.twig', [
            'famille' => $famille,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="famille_show", methods={"GET"})
     */
    public function show(Famille $famille): Response
    {
        return $this->render('famille/show.html.twig', [
            'famille' => $famille,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="famille_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Famille $famille, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        $slugify = new Slugify();
	        $famille->setSlug($slugify->slugify($famille->getLibelle()));
	        $famille->setLibelle($famille->getLibelle());
			
            $entityManager->flush();
			
			$this->addFlash('success', "La famille ".$famille->getLibelle()." a bien été modifiée");

            return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('famille/edit.html.twig', [
            'famille' => $famille,
            'form' => $form,
	        'familles' => $this->familleRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="famille_delete", methods={"POST"})
     */
    public function delete(Request $request, Famille $famille, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$famille->getId(), $request->request->get('_token'))) {
            $entityManager->remove($famille);
            $entityManager->flush();
			
			$this->addFlash('warning', "La famille ".$famille->getLibelle()." a été supprimée avec succès!");
        }

        return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * Verification de l'existence de la famille
	 *
	 * @param $nom
	 * @return bool
	 */
	function verificationExistence($nom): bool
	{
		$famille = $this->familleRepository->findOneBy(['libelle'=>$nom]);
		if ($famille) return true;
		else return false;
	}
}

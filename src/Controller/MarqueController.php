<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marque")
 */
class MarqueController extends AbstractController
{
	private $_em;
	private $marqueRepository;
	
	public function __construct(EntityManagerInterface $_em, MarqueRepository $marqueRepository)
	{
		$this->_em = $_em;
		$this->marqueRepository = $marqueRepository;
	}
	
    /**
     * @Route("/", name="marque_index", methods={"GET","POST"})
     */
    public function index(Request $request, MarqueRepository $marqueRepository): Response
    {
	    $marque = new Marque();
	    $form = $this->createForm(MarqueType::class, $marque);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
			
			// Verification de l'existence de la marque
		    if ($this->verification($marque->getNom()))
			    return $this->redirectToRoute('marque_index', [], Response::HTTP_SEE_OTHER);
			
			$slugify = new Slugify();
			$marque->setSlug($slugify->slugify($marque->getNom()));
			$marque->setNom(strtoupper($marque->getNom()));
			
		    $this->_em->persist($marque);
		    $this->_em->flush();
			
			$this->addFlash('success', "La marque ".$marque->getNom()." a bien été ajoutée.");
		
		    return $this->redirectToRoute('marque_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->renderForm('marque/index.html.twig', [
            'marques' => $marqueRepository->findAll(),
	        'marque' => $marque,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="marque_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('marque/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="marque_show", methods={"GET"})
     */
    public function show(Marque $marque): Response
    {
        return $this->render('marque/show.html.twig', [
            'marque' => $marque,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="marque_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        $slugify = new Slugify();
	        $marque->setSlug($slugify->slugify($marque->getNom()));
	        $marque->setNom(strtoupper($marque->getNom()));
			
            $entityManager->flush();
	
	        $this->addFlash('success', "La marque ".$marque->getNom()." a bien été modifiée.");
	
	        return $this->redirectToRoute('marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('marque/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
	        'marques' => $this->marqueRepository->findBy([],['nom'=>"ASC"])
        ]);
    }

    /**
     * @Route("/{id}", name="marque_delete", methods={"POST"})
     */
    public function delete(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {
            $entityManager->remove($marque);
            $entityManager->flush();
			
			$this->addFlash('success', "La marque ".$marque->getNom()." a bien été supprimée!");
        }

        return $this->redirectToRoute('marque_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * Verification de l'existence de la marque dans la base de données
	 *
	 * @param $nom
	 * @return bool
	 */
	protected function verification($nom): bool
	{
		$verif = $this->marqueRepository->findOneBy(['nom' => $nom]);
		if ($verif){
			$this->addFlash('danger', "La marque ".$nom." a déjà été enregistrée.");
			return true;
		}
		else return false;
	}
}

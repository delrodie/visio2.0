<?php

namespace App\Controller;

use App\Entity\Traitement;
use App\Form\TraitementType;
use App\Repository\TraitementRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/traitement")
 */
class TraitementController extends AbstractController
{
	private $_em;
	private $traitementRepository;
	
	public function __construct(EntityManagerInterface $_em, TraitementRepository $traitementRepository)
	{
		$this->_em = $_em;
		$this->traitementRepository = $traitementRepository;
	}
	
    /**
     * @Route("/", name="traitement_index", methods={"GET","POST"})
     */
    public function index(Request $request, TraitementRepository $traitementRepository): Response
    {
	    $traitement = new Traitement();
	    $form = $this->createForm(TraitementType::class, $traitement);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
			
			// Verification du traitement dans le système
		    if ($this->verification($traitement->getLibelle())){
				$this->addFlash('danger', "Le traitement ".$traitement->getLibelle()." a déjà été enregistré");
			    return $this->redirectToRoute('traitement_index', [], Response::HTTP_SEE_OTHER);
		    }
			
			$slugify = new Slugify();
			$traitement->setSlug($slugify->slugify($traitement->getLibelle()));
			$traitement->setLibelle(strtoupper($traitement->getLibelle()));
		    $this->_em->persist($traitement);
		    $this->_em->flush();
			
			$this->addFlash('success', "Le traitement ".$traitement->getLibelle()." a bien été ajouté!");
		
		    return $this->redirectToRoute('traitement_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->renderForm('traitement/index.html.twig', [
            'traitements' => $traitementRepository->findAll(),
	        'traitement' => $traitement,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="traitement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $traitement = new Traitement();
        $form = $this->createForm(TraitementType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($traitement);
            $entityManager->flush();

            return $this->redirectToRoute('traitement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traitement/new.html.twig', [
            'traitement' => $traitement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="traitement_show", methods={"GET"})
     */
    public function show(Traitement $traitement): Response
    {
        return $this->render('traitement/show.html.twig', [
            'traitement' => $traitement,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="traitement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Traitement $traitement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TraitementType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        $slugify = new Slugify();
	        $traitement->setSlug($slugify->slugify($traitement->getLibelle()));
	        $traitement->setLibelle(strtoupper($traitement->getLibelle()));
			
            $entityManager->flush();
	
	        $this->addFlash('success', "Le traitement ".$traitement->getLibelle()." a bien été modifié!");
	
	        return $this->redirectToRoute('traitement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traitement/edit.html.twig', [
            'traitement' => $traitement,
            'form' => $form,
	        'traitements' => $this->traitementRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="traitement_delete", methods={"POST"})
     */
    public function delete(Request $request, Traitement $traitement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$traitement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($traitement);
            $entityManager->flush();
			
			$this->addFlash('success', "Le traitement ".$traitement->getLibelle()." a bien été supprimé");
        }

        return $this->redirectToRoute('traitement_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * Verification de l'existence du traitement
	 *
	 * @param $nom
	 * @return bool
	 */
	protected function verification($nom): bool
	{
		$verif = $this->traitementRepository->findOneBy(['libelle'=>$nom]);
		if ($verif) return true;
		else return false;
	}
}

<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Utilities\GestionMedia;
use App\Utilities\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entreprise")
 */
class EntrepriseController extends AbstractController
{
	private $gestionMedia;
	private $utility;
	
	public function __construct(GestionMedia $gestionMedia, Utility $utility)
	{
		$this->gestionMedia = $gestionMedia;
		$this->utility = $utility;
	}
	
    /**
     * @Route("/", name="entreprise_index", methods={"GET"})
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
		$entreprise = $entrepriseRepository->findOneBy([],['id'=>'ASC']);
		if (!$entreprise)
			return $this->redirectToRoute('entreprise_new', [], Response::HTTP_SEE_OTHER);
		else{
			return $this->redirectToRoute('entreprise_show',['slug'=>$entreprise->getSlug()], Response::HTTP_SEE_OTHER);
		}
    }

    /**
     * @Route("/new", name="entreprise_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	
	        $mediaFile = $form->get('media')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'entreprise'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');
		
		        $entreprise->setMedia($media);
	        }
			
			$slug = $this->utility->slug($entreprise->getNom());
			$entreprise->setSlug($slug);
			
            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="entreprise_show", methods={"GET"})
     */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="entreprise_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_delete", methods={"POST"})
     */
    public function delete(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entreprise_index', [], Response::HTTP_SEE_OTHER);
    }
}

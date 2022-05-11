<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use App\Utilities\GestionFacture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
	private $_facture;
	
	public function __construct(GestionFacture $_facture)
	{
		$this->_facture = $_facture;
	}
	
    /**
     * @Route("/", name="facture_index", methods={"GET"})
     */
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{slug}", name="facture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, $slug): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture, ['client' => $slug]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();
			
	        $this->_facture->numero($facture);
			
			if ($facture->getMontureBool()){
				return $this->redirectToRoute('facture_complement_monture',['factureId'=>$facture->getId()]);
			}

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
	        'client' => $entityManager ->getRepository(Client::class)->findOneBy(['slug'=>$slug])
        ]);
    }

    /**
     * @Route("/{id}", name="facture_show", methods={"GET"})
     */
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="facture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture, ['client' => $facture->getClient()->getSlug()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$this->addFlash('danger', 'Vous ne pouvez pas modifier cette facture. Nous vous invitons à la supprimer puis la reprendre');
            
            return $this->redirectToRoute('facture_edit', ["id"=>$facture->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
	        'client' => $entityManager->getRepository(Client::class)->findOneBy(['id' => $facture->getClient()->getId()]),
        ]);
    }

    /**
     * @Route("/{id}", name="facture_delete", methods={"POST"})
     */
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
			$monture = $facture->getMonture();
			if ($facture->getMontureBool() && $monture){
				$monture->setStock($monture->getStock()+1);
			}
			//dd($facture);
	        // Mise a jour du solde du client
	        $client = $facture->getClient();
	        $solde = $client->getSolde() - $facture->getRap();
			if(0 > $solde) $client->setSolde(0);
	        else $client->setSolde($solde);
			
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
    }
}

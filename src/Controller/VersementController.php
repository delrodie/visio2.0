<?php

namespace App\Controller;

use App\Entity\Versement;
use App\Form\VersementType;
use App\Repository\FactureRepository;
use App\Repository\VersementRepository;
use App\Utilities\GestionFacture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/versement')]
class VersementController extends AbstractController
{
	private $gestionFacture;
	private FactureRepository $factureRepository;
	
	public function __construct(GestionFacture $gestionFacture, FactureRepository $factureRepository)
	{
		$this->gestionFacture = $gestionFacture;
		$this->factureRepository = $factureRepository;
	}
	
    #[Route('/', name: 'app_versement_index', methods: ['GET'])]
    public function index(VersementRepository $versementRepository): Response
    {
		//dd($versementRepository->findList());
        return $this->render('versement/index.html.twig', [
            'versements' => $versementRepository->findList(),
        ]);
    }

    #[Route('/new/{factureId}', name: 'app_versement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VersementRepository $versementRepository, $factureId): Response
    {
		$facture = $this->factureRepository->findOneBy(['id'=>$factureId]);
        $versement = new Versement();
        $form = $this->createForm(VersementType::class, $versement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			if ($versement->getAcompte() > $facture->getRap()){
				$this->addFlash('danger', "Attention!! Le montant versé doit être inferieur ou egal au reste à payer");
				return $this->redirectToRoute('app_versement_new',['factureId' => $facture->getId()]);
			}
			$versement->setReference($this->gestionFacture->reference_versement());
			$versement->setFacture($facture);
			$versement->setReste($facture->getRap() - $versement->getAcompte());
			
            $versementRepository->add($versement, true);
			
			$this->gestionFacture->majMontantFacture($facture, $versement->getAcompte(), true);

            return $this->redirectToRoute('app_versement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('versement/new.html.twig', [
            'versement' => $versement,
            'form' => $form,
	        'facture' => $facture
        ]);
    }

    #[Route('/{id}', name: 'app_versement_show', methods: ['GET'])]
    public function show(Versement $versement): Response
    {
        return $this->render('versement/show.html.twig', [
            'versement' => $versement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_versement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Versement $versement, VersementRepository $versementRepository): Response
    {
        $form = $this->createForm(VersementType::class, $versement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $versementRepository->add($versement, true);

            return $this->redirectToRoute('app_versement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('versement/edit.html.twig', [
            'versement' => $versement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_versement_delete', methods: ['POST'])]
    public function delete(Request $request, Versement $versement, VersementRepository $versementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$versement->getId(), $request->request->get('_token'))) {
            $versementRepository->remove($versement, true);
        }

        return $this->redirectToRoute('app_versement_index', [], Response::HTTP_SEE_OTHER);
    }
}

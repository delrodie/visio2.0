<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etat")
 */
class EtatController extends AbstractController
{
    /**
     * @Route("/{id}", name="etat_facture", methods={"GET"})
     */
    public function index(Facture $facture): Response
    {
		//dd($facture);
        return $this->render('etat/index.html.twig', [
            'facture' => $facture,
        ]);
    }
	
	/**
	 * @Route("/entreprise/information", name="etat_facture_entreprise", methods={"GET"})
	 */
	public function entreprise(EntrepriseRepository $entrepriseRepository): Response
	{
		return $this->render('etat/entreprise.html.twig',[
			'entreprise' => $entrepriseRepository->findOneBy([], ['id'=>'DESC']),
		]);
	}
}

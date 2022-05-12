<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Marque;
use App\Entity\Monture;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(EntityManagerInterface $entityManager, FactureRepository $factureRepository): Response
    {
		$factures = $entityManager->getRepository(Facture::class)->findByPeriode(date('Y-m-01'), date('Y-m-31'));
		$mois=[];
	    for ($i = 1; $i <= 12; $i++) {
		    if ($i < 10) $j = '0'.$i;
			else $j = $i;
			$montantHt = $factureRepository->getMontantHTByPeriode(date('Y-'.$j.'-01'), date('Y-'.$j.'-31'));
			$remise = $factureRepository->getRemiseByPeriode(date('Y-'.$j.'-01'), date('Y-'.$j.'-31'));
			$mois[$i] = $montantHt - $remise;
	    }
		
		//dd($mois);
        return $this->render('home/index.html.twig', [
            'marque' => count($entityManager->getRepository(Marque::class)->findAll()),
	        'client' => count($entityManager->getRepository(Client::class)->findAll()),
	        'monture' => $entityManager->getRepository(Monture::class)->getStock(),
	        'nombre_facture' => count($factures),
	        'mois' => $mois,
	        'annee' => date('Y')
        ]);
    }
	
	/**
	 * @Route("/date", name="app_date")
	 */
	public function date(): Response
	{
		return new Response(date('Y-m-d H:i:s'));
	}
}

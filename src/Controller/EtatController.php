<?php

namespace App\Controller;

use App\Entity\Facture;
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
		dd($facture);
        return $this->render('etat/index.html.twig', [
            'controller_name' => 'EtatController',
        ]);
    }
}

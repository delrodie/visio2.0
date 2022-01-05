<?php

namespace App\Controller;

use App\Entity\Monture;
use App\Form\MontureType;
use App\Repository\MontureRepository;
use App\Utilities\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/monture")
 */
class MontureController extends AbstractController
{
	private $_em;
	private $montureRepository;
	/**
	 * @var Utility
	 */
	private $_utility;
	
	public function __construct(EntityManagerInterface $_em, MontureRepository $montureRepository, Utility $_utility)
	{
		$this->_em = $_em;
		$this->montureRepository = $montureRepository;
		$this->_utility = $_utility;
	}
	
    /**
     * @Route("/", name="monture_index", methods={"GET"})
     */
    public function index(MontureRepository $montureRepository): Response
    {
        return $this->render('monture/index.html.twig', [
            'montures' => $montureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="monture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $monture = new Monture();
        $form = $this->createForm(MontureType::class, $monture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			// Verification de non existence
	        if ($this->verification($monture))
				return $this->redirectToRoute('monture_index', [], Response::HTTP_SEE_OTHER);
			
			$monture->setSlug($this->_utility->slug($monture->getMarque().'-'.$monture->getReference()));
			$monture->setReference(strtoupper($monture->getReference()));
			$monture->setStock($this->stock($monture)); //dd($monture);
			
            $entityManager->persist($monture);
            $entityManager->flush();
			
			$this->addFlash('success', "La monture ".$monture->getMarque()->getNom().' - '.$monture->getReference()." a été ajoutée avec succès!");

            return $this->redirectToRoute('monture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('monture/new.html.twig', [
            'monture' => $monture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="monture_show", methods={"GET"})
     */
    public function show(Monture $monture): Response
    {
        return $this->render('monture/show.html.twig', [
            'monture' => $monture,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="monture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Monture $monture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MontureType::class, $monture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        $monture->setSlug($this->_utility->slug($monture->getMarque().'-'.$monture->getReference()));
	        $monture->setReference(strtoupper($monture->getReference()));
			
            $entityManager->flush();

            return $this->redirectToRoute('monture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('monture/edit.html.twig', [
            'monture' => $monture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="monture_delete", methods={"POST"})
     */
    public function delete(Request $request, Monture $monture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($monture);
            $entityManager->flush();
			
			$this->addFlash('warning', "La monture ".$monture->getMarque()->getNom().'-'.$monture->getReference()." a été supprimée avec succès!");
        }

        return $this->redirectToRoute('monture_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * Verification d'existence dans le système
	 *
	 * @param $monture
	 * @return bool
	 */
	protected function verification($monture): bool
	{
		$verif =$this->_em->getRepository(Monture::class)->findOneBy(['reference' => $monture->getReference(), 'marque' => $monture->getMarque()]);
		if ($verif){
			$this->addFlash('danger', "La monture ".$monture->getMarque()->getNom().' - '.$monture->getReference()." a déjà été ajoutée!");
			return true;
		}else return false;
	}
	
	/**
	 * @param $monture
	 * @return int
	 */
	protected function stock($monture): int
	{
		if ($monture->getStock()) return (int)$monture->getStock();
		else return 1;
	}
}

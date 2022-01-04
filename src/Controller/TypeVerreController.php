<?php

namespace App\Controller;

use App\Entity\TypeVerre;
use App\Form\TypeVerreType;
use App\Repository\TypeVerreRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/verre")
 */
class TypeVerreController extends AbstractController
{
	private $_em;
	private $typeVerreRepository;
	
	public function __construct(EntityManagerInterface $_em, TypeVerreRepository $typeVerreRepository)
	{
		$this->_em = $_em;
		$this->typeVerreRepository = $typeVerreRepository;
	}
	
    /**
     * @Route("/", name="type_verre_index", methods={"GET", "POST"})
     */
    public function index(Request $request, TypeVerreRepository $typeVerreRepository): Response
    {
	    $typeVerre = new TypeVerre();
	    $form = $this->createForm(TypeVerreType::class, $typeVerre);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
			
			// verification de l'existence du type de verre dans le système
		    if ($this->verification($typeVerre->getLibelle())){
				$this->addFlash('danger', "Le type de verre ".$typeVerre->getLibelle()." a été ajouté avec succès!");
			    return $this->redirectToRoute('type_verre_index', [], Response::HTTP_SEE_OTHER);
		    }
			
			$slugify = new Slugify();
			$typeVerre->setSlug($slugify->slugify($typeVerre->getLibelle()));
			$typeVerre->setLibelle(strtoupper($typeVerre->getLibelle()));
			
		    $this->_em->persist($typeVerre);
		    $this->_em->flush();
			
			$this->addFlash('success', "Le type de verre ".$typeVerre->getLibelle()." a bien été ajouté!");
		
		    return $this->redirectToRoute('type_verre_index', [], Response::HTTP_SEE_OTHER);
	    }
	
	    return $this->renderForm('type_verre/index.html.twig', [
            'type_verres' => $typeVerreRepository->findAll(),
		    'type_verre' => $typeVerre,
		    'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="type_verre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeVerre = new TypeVerre();
        $form = $this->createForm(TypeVerreType::class, $typeVerre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeVerre);
            $entityManager->flush();

            return $this->redirectToRoute('type_verre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_verre/new.html.twig', [
            'type_verre' => $typeVerre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="type_verre_show", methods={"GET"})
     */
    public function show(TypeVerre $typeVerre): Response
    {
        return $this->render('type_verre/show.html.twig', [
            'type_verre' => $typeVerre,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="type_verre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypeVerre $typeVerre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeVerreType::class, $typeVerre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        $slugify = new Slugify();
	        $typeVerre->setSlug($slugify->slugify($typeVerre->getLibelle()));
	        $typeVerre->setLibelle(strtoupper($typeVerre->getLibelle()));
			
            $entityManager->flush();
	
	        $this->addFlash('success', "Le type de verre ".$typeVerre->getLibelle()." a bien été modifié!");
	
	        return $this->redirectToRoute('type_verre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_verre/edit.html.twig', [
            'type_verre' => $typeVerre,
            'form' => $form,
	        'type_verres' => $this->typeVerreRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="type_verre_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeVerre $typeVerre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeVerre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeVerre);
            $entityManager->flush();
			
			$this->addFlash('danger', "Le type de verre ".$typeVerre->getLibelle()." a été supprimé avec succès!");
        }

        return $this->redirectToRoute('type_verre_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * Verification de l'existence du type de verre dans le système
	 *
	 * @param $nom
	 * @return bool
	 */
	protected function verification($nom): bool
	{
		$verif = $this->typeVerreRepository->findOneBy(['libelle'=>$nom]);
		if ($verif) return true;
		else return false;
	}
}

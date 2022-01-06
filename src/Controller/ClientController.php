<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Utilities\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
	private $_utility;
	private $_em;
	
	public function __construct(Utility $_utility, EntityManagerInterface $_em)
	{
		$this->_utility = $_utility;
		$this->_em = $_em;
	}
	
    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			// Verification de l'unicité du client
	        if ($this->verification($client))
				return $this->redirectToRoute('client_index',[], Response::HTTP_SEE_OTHER);
			
			$client->setMatricule($this->_utility->matricule());
			$client->setSlug($this->_utility->slug($client->getNom().'-'.$client->getPrenoms().'-'.$this->_utility->matricule()));
			$client->setNom(strtoupper($client->getNom()));
			$client->setPrenoms(strtoupper($client->getPrenoms()));
            $entityManager->persist($client);
            $entityManager->flush();
			
			$this->addFlash('success', "Le client ".$client->getNom().' '.$client->getPrenoms()." a été ajouté avec succès!");

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="client_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        $client->setSlug($this->_utility->slug($client->getNom().'-'.$client->getPrenoms().'-'.$this->_utility->matricule()));
	        $client->setNom(strtoupper($client->getNom()));
	        $client->setPrenoms(strtoupper($client->getPrenoms()));
            $entityManager->flush();
	
	        $this->addFlash('success', "Le client ".$client->getNom().' '.$client->getPrenoms()." a été modifié avec succès!");
	
	        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
			
			$this->addFlash('success', "Le client ".$client->getNom().' '.$client->getPrenoms()." a bien été supprimé!");
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * Verification de l'existence du client
	 *
	 * @param $client
	 * @return bool
	 */
	protected function verification($client): bool
	{
		$verif = $this->_em->getRepository(Client::class)->findOneBy([
			'nom' => $client->getNom(),
			'prenoms' => $client->getPrenoms(),
			'cel' => $client->getCel()
		]);
		if ($verif) {
			$this->addFlash('danger', "Le client ".$client->getNom().' '.$client->getPrenoms()." existe déjà dans le système!");
			return true;
		}
		else return false;
	}
}

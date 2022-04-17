<?php
	
	namespace App\Controller;
	
	use App\Entity\Facture;
	use App\Entity\Monture;
	use App\Form\FactureMontureType;
	use App\Repository\FactureRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Serializer\Encoder\JsonEncoder;
	use Symfony\Component\Serializer\Encoder\XmlEncoder;
	use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
	use Symfony\Component\Serializer\Serializer;
	
	/**
	 * @Route("/facture/complement")
	 */
	class FactureComplementController extends AbstractController
	{
		private $factureRepository;
		private $em;
		
		public function __construct(FactureRepository $factureRepository, EntityManagerInterface $em)
		{
			$this->factureRepository = $factureRepository;
			$this->em = $em;
		}
		
		/**
		 * @Route("/{factureId}", name="facture_complement_monture", methods={"GET", "POST"})
		 */
		public function monture(Request $request, $factureId)
		{
			$facture = $this->factureRepository->findOneBy(['id'=>$factureId]);
			$form = $this->createForm(FactureMontureType::class, $facture);
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$montant = (int)$facture->getMontantHt() + (int)$facture->getPrixMonture();
				$facture->setMontantHt($montant);
				
				// Mise a jour du stock de la monture
				$stock = $facture->getMonture()->getStock();
				$facture->getMonture()->setStock($stock - 1);
				
				//dd($facture);
				$this->em->flush();
				
				if ($facture->getVerreBool()){
					return $this->redirectToRoute('etat_facture',['id'=>$facture->getId()]);
				}
				
				return $this->redirectToRoute('facture_complement_finalisation',['id'=>$facture->getId()]);
			}
			
			return $this->renderForm('facture/monture.html.twig', [
				'facture' => $facture,
				'form' => $form,
			]);
			
		}
		
		/**
		 * @Route("/finalisation/{id}", name="facture_complement_finalisation", methods={"GET","POST"})
		 */
		public function finalisation(Request $request, Facture $facture): Response
		{
			if($request->getMethod() === 'POST'){
				$remise = $request->get('facture_remise');
				$assurance = $request->get('facture_assurance');
				$acompte = $request->get('facture_acompte');
				$rap = $request->get('facture_resteAPayer');
				
				$facture->setRemise($remise);
				$facture->setPartAssurance($assurance);
				$facture->setAccompte($acompte);
				$facture->setRap($rap);
				
				$this->em->flush();
				
				return $this->redirectToRoute('etat_facture', ['id' => $facture->getId()]);
			}
			return $this->render('facture/finalisation.html.twig',[
				'facture' => $facture
			]);
		}
		
		/**
		 * @Route("/ajax/monture/de", name="facture_completement_ajax", methods={"GET"})
		 */
		public function ajax(Request $request)
		{
			//Initialisation
			$encoders = [new XmlEncoder(), new JsonEncoder()];
			$normalizers = [new ObjectNormalizer()];
			$serializer = new Serializer($normalizers, $encoders);
			
			$monture = $request->get('res');
			$resultat = $this->em->getRepository(Monture::class)->findOneBy(['id'=>$monture]);
			
			$data = $this->json($resultat->getMontant());
			
			return $data;
		}
	}

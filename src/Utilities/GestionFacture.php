<?php
	
	namespace App\Utilities;
	
	use App\Entity\Versement;
	use App\Repository\FactureRepository;
	use App\Repository\VersementRepository;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionFacture
	{
		private $em;
		private $factureRepository;
		private $versementRepository;
		
		public function __construct(EntityManagerInterface $em, FactureRepository $factureRepository, VersementRepository $versementRepository
		)
		{
			$this->em = $em;
			$this->factureRepository = $factureRepository;
			$this->versementRepository = $versementRepository;
		}
		
		/**
		 * Generation du numero de la facture
		 *
		 * @param $facture
		 * @return bool
		 */
		public function numero($facture): bool
		{
			$date_souche = date('ym');
			$date_jour = date('d');
			$ordre = $this->position($facture->getId());
			
			$numero = $date_souche.'/'.$ordre;
			
			$facture->setNumero($numero);
			$this->em->flush();
			
			return true;
		}
		
		/**
		 * @param $facture
		 * @return bool|string
		 */
		public function reference_versement($facture = null)
		{
			$versement = $this->versementRepository->findOneBy([],['id'=>"DESC"]);
			if (!$versement) $ref = 1;
			else $ref = (int)$versement->getId() + 1;
			
			$date_souche = date('ym');
			$ordre = $this->position($ref);
			
			$numero = $date_souche.'/'.$ordre;
			
			if ($facture){
				$montant = $facture->getMontantHt() - $facture->getRemise();
				$reste = $montant - $facture->getAccompte();
				
				$entity = new Versement();
				$entity->setAcompte($facture->getAccompte());
				$entity->setDate($facture->getDate());
				$entity->setMontant($montant);
				$entity->setReste($reste);
				$entity->setReference($numero);
				$entity->setFacture($facture);
				
				$this->versementRepository->add($entity, true);
				
				return true;
			}else{
				return $numero;
			}
		}
		
		public function majMontantFacture($facture, int $montant_verse, bool $add = null)
		{
			if ($add){
				$resteApayer = $facture->getRap() - $montant_verse;
				$facture->setRap($resteApayer);
				$solde_client = $facture->getClient()->getSolde() - $montant_verse;
				$facture->getClient()->setSolde($solde_client);
				$this->em->flush();
			}else{
				$resteApayer = $facture->getRap() + $montant_verse;
				$facture->setRap($resteApayer);
				$solde_client = $facture->getClient()->getSolde() + $montant_verse;
				$facture->getClient()->setSolde($solde_client);
				$this->em->flush();
			}
			
			return true;
		}
		
		function position($id)
		{
			if ($id < 10){
				$res = '000'.$id;
			}elseif ($id < 100){
				$res = '00'.$id;
			}elseif ($id < 1000){
				$res = '0'.$id;
			}else{
				$res = $id;
			}
			
			return $res;
		}
	}

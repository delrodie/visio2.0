<?php
	
	namespace App\Utilities;
	
	use App\Repository\FactureRepository;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionFacture
	{
		private $em;
		private $factureRepository;
		
		public function __construct(EntityManagerInterface $em, FactureRepository $factureRepository
		)
		{
			$this->em = $em;
			$this->factureRepository = $factureRepository;
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

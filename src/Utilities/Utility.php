<?php
	
	namespace App\Utilities;
	
	use App\Entity\Client;
	use Cocur\Slugify\Slugify;
	use Doctrine\ORM\EntityManagerInterface;
	
	class Utility
	{
		private $_em;
		
		public function __construct(EntityManagerInterface $_em)
		{
			$this->_em = $_em;
		}
		
		/**
		 * Generation du string
		 *
		 * @param $valeur
		 * @return string
		 */
		public function slug($valeur): string
		{
			$slugify = new Slugify();
			return  $slugify->slugify($valeur);
		}
		
		/**
		 * Generation du matricule du client
		 *
		 * @return string
		 */
		public function matricule(): string
		{
			$res = true;
			while ($res){
				$matricule = date('ym').''.mt_rand(100,999);
				$client = $this->_em->getRepository(Client::class)->findOneBy(['matricule'=>$matricule]);
				if ($client) $res = true;
				else $res = false;
			}
			return $matricule;
		}
	}

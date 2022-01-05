<?php
	
	namespace App\Utilities;
	
	use Cocur\Slugify\Slugify;
	
	class Utility
	{
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
	}

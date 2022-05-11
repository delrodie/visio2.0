<?php
	
	namespace App\Utilities;
	
	use Cocur\Slugify\Slugify;
	use Symfony\Component\HttpFoundation\File\Exception\FileException;
	use Symfony\Component\HttpFoundation\File\UploadedFile;
	
	class GestionMedia
	{
		private $mediaEntreprise;
		
		public function __construct($entrepriseDirectory)
		{
			$this->mediaEntreprise = $entrepriseDirectory;
		}
		
		
		/**
		 * Enregistrement du fichier dans le repertoire approprié
		 *
		 * @param UploadedFile $file
		 * @param null $media
		 * @return string
		 */
		public function upload(UploadedFile $file, $media = null): string
		{
			// Initialisation du slug
			$slugify = new Slugify(); //dd($file);
			
			$originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $slugify->slugify($originalFileName);
			$newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension(); //dd($this->mediaActivite);
			
			// Deplacement du fichier dans le repertoire dedié
			try {
				if ($media === 'entreprise') $file->move($this->mediaEntreprise, $newFilename);
				else $file->move($this->mediaEntreprise, $newFilename);
			}catch (FileException $e){
			
			}
			
			return $newFilename;
		}
		
		/**
		 * Suppression de l'ancien media sur le server
		 *
		 * @param $ancienMedia
		 * @param null $media
		 * @return bool
		 */
		public function removeUpload($ancienMedia, $media = null): bool
		{
			if ($media === 'entreprise') unlink($this->mediaEntreprise.'/'.$ancienMedia);
			else return false;
			
			return true;
		}
	}

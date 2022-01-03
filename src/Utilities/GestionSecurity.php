<?php
	
	namespace App\Utilities;
	
	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Security\Core\Security;
	
	class GestionSecurity
	{
		private $_em;
		private $passwordHasher;
		private $_security;
		
		public function __construct(EntityManagerInterface $_em, UserPasswordHasherInterface $passwordHasher, Security $_security)
		{
			$this->_em = $_em;
			$this->passwordHasher = $passwordHasher;
			$this->_security = $_security;
		}
		
		/**
		 * Initialisation de la table USER
		 *
		 * @return bool
		 */
		public function initialisation(): bool
		{
			$delrodie = $this->_em->getRepository(User::class)->findOneBy(['username'=>'delrodie']);
			if (!$delrodie){
				$user = new User();
				$user->setUsername('delrodie');
				$user->setPassword($this->passwordHasher->hashPassword($user, 'optic2022'));
				$user->setRoles(['ROLE_SUPER_ADMIN']);
				$this->_em->persist($user);
				$this->_em->flush();
				
				return true;
			}
			return false;
		}
		
		/**
		 * Incrementation du nombre de connexion correspondant à l'utilisateur
		 *
		 * @return bool
		 */
		public function connexion(): bool
		{
			$user = $this->_em->getRepository(User::class)->findOneBy(['username' => $this->_security->getUser()->getUserIdentifier()]);
			
			$nombre_connexion = (int)$user->getLoginCount();
			$user->setLoginCount($nombre_connexion+1);
			$user->setLastConnectedAt(new \DateTime());
			$this->_em->flush();
			
			return true;
		}
	}

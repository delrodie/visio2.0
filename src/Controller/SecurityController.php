<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Utilities\GestionSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	private $_security;
	
	public function __construct(GestionSecurity $_security)
	{
		$this->_security = $_security;
	}
	
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
	    
	    // Initialisation de la table Utilisateur
	    if ($this->_security->initialisation())
			$this->addFlash('success', "Utilisateur 'optic2022' initialisé avec succès");

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
	
	/**
	 * @Route("/change-password/", name="security_change_password", methods={"GET","POST"})
	 */
	public function changepassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
	{
		
		$user = $entityManager->getRepository(User::class)->findOneBy(['username'=>$this->getUser()->getUserIdentifier()]);
		//$user = new User();
		$form = $this->createForm(ChangePasswordType::class, $user); //dd($user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){ //dd($user);
			// Encode du nouveau mot de passe
			if ($user->getPassword()){
				$password = $passwordHasher->hashPassword($user, $user->getPassword());
				$user->setPassword($password);
			}
			$entityManager->flush();
			
			$this->addFlash('success', "Vos informations ont été mises à jour avec succès. Veuillez-vous reconnecter");
			
			return $this->redirectToRoute('app_login');
		}
		//dd($form);
		return $this->renderForm('security/change_password.html.twig',[
			'form'=>$form,
			'user' => $user
		]);
	}
}

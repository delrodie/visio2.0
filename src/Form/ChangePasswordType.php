<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
				'attr'=>['class'=>'form-control form-control-user', 'readonly'=>true],
	            'label' => 'Nom utilisateur'
            ])
            //->add('roles')
            ->add('password', PasswordType::class,[
				'attr'=>['class'=>'form-control form-control-user', 'placeholder'=>"Mot de passe"],
	            'label' => "Mot de passe"
            ])
            //->add('loginCount')
            //->add('lastConnectedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

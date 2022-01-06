<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Nom de famille", 'autocomplete'=>"off"],
	            'label'=>"Nom *"
            ])
            ->add('prenoms', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Prenoms", 'autocomplete'=>"off"],
	            'label'=>"Prenoms *"
            ])
            ->add('sexe', ChoiceType::class,[
	            'attr' => ['class'=>'form-control'],
	            'choices'=>[
		            '-- Selectionnez le sexe --' => '',
		            '' => '',
		            'Femme' => 'FEMME',
		            'Homme' => 'HOMME',
	            ],
	            'multiple'=> false,
	            'expanded'=>false,
	            'label' => "Sexe *"
            ])
            ->add('adresse', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Adresse", 'autocomplete'=>"off"],
	            'label'=>"Adresse",
	            'required' => false
            ])
            ->add('cel', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Numero de cellulaire", 'autocomplete'=>"off"],
	            'label'=>"Cellulaire *"
            ])
            ->add('tel', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Numéro de telephone", 'autocomplete'=>"off"],
	            'label'=>"Telephone",
	            'required' => false
            ])
            //->add('slug')
            //->add('solde')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}

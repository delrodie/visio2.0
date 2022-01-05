<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Monture;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MontureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"La reference de la monture", 'autocomplete'=>"off"],
	            'label'=>"Reference *"
            ])
            ->add('montant', IntegerType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Le montant", 'autocomplete'=>"off"],
	            'label'=>'Montant *'
            ])
            ->add('genre', ChoiceType::class,[
	            'attr' => ['class'=>'form-control'],
	            'choices'=>[
		            '-- Selectionnez le genre --' => '',
		            '' => '',
		            'Enfant' => 'ENFANT',
		            'Femme' => 'FEMME',
		            'Homme' => 'HOMME',
		            'Mixte' => 'MIXTE',
		            'Soleil' => 'SOLEIL',
	            ],
	            'multiple'=> false,
	            'expanded'=>false,
	            'label' => "Genre *"
            ])
            ->add('col', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"La couleur de la monture", 'autocomplete'=>"off"],
	            'label'=>"Coleur",
	            'required' => false
            ])
            ->add('taille', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"la taille", 'autocomplete'=>"off"],
	            'label'=>"Taille",
	            'required' => false
            ])
            ->add('stock', IntegerType::class,[
	            'attr'=>['class'=>'form-control', 'placeholder'=>"Le nombre en stock", 'autocomplete'=>"off"],
	            'label'=>'Stock',
	            'required' => false,
	            'help_attr' => ['style'=>"font-style: italic; color:red;"],
	            'help' => "Ignorer si le nombre est 1"
            ])
            //->add('slug')
            ->add('marque', EntityType::class,[
	            'attr' => ['class'=>"form-control"],
				'class' => Marque::class,
	            'query_builder' => function(EntityRepository $entityRepository){
					return $entityRepository->createQueryBuilder('m')
						->orderBy('m.nom', "ASC");
	            },
	            'choice_label' => 'nom',
	            'label' => "Marque *"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monture::class,
        ]);
    }
}

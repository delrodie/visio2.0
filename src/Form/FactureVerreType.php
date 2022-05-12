<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\TypeVerre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureVerreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('numero')
            //->add('montantHt')
            //->add('remise')
            //->add('tva')
            //->add('montantTTC')
            //->add('accompte')
            //->add('rap')
            //->add('partAssurance')
            //->add('date')
            ->add('odcyl', ChoiceType::class,[
				'attr'=>['class'=>'form-control select2'],
	            'choices' =>[
					'+2,50' => '+2,50',
					'+2,25' => '+2,25',
					'+2,00' => '+2,00',
					'+1,75' => '+1,75',
					'+1,50' => '+1,50',
					'+1,25' => '+1,25',
					'+1,00' => '+1,00',
					'+0,75' => '+0,75',
					'+0,50' => '+0,50',
					'+0,25' => '+0,25',
					'PLAN' => 'PLAN',
					'-0,25' => '-0,25',
					'-0,50' => '-0,50',
					'-0,75' => '-0,75',
					'-1,00' => '-1,00',
					'-1,25' => '-1,25',
					'-1,50' => '-1,50',
					'-1,75' => '-1,75',
					'-2,00' => '-2,00',
					'-2,25' => '-2,25',
					'-2,50' => '-2,50',
	            ],
	            'label' => 'OD Cylindre'
            ])
            ->add('odAxe', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OD Axe',
	            'required' => false
            ])
            ->add('odAdd', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OD Add',
	            'required' => false
            ])
            ->add('odQte', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OD Quantite',
	            'required' => false
            ])
            ->add('odMontant', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OD Montant',
	            'required' => true
            ])
            ->add('odSph', ChoiceType::class,[
	            'attr'=>['class'=>'form-control select2'],
	            'choices' =>[
		            '+2,50' => '+2,50',
		            '+2,25' => '+2,25',
		            '+2,00' => '+2,00',
		            '+1,75' => '+1,75',
		            '+1,50' => '+1,50',
		            '+1,25' => '+1,25',
		            '+1,00' => '+1,00',
		            '+0,75' => '+0,75',
		            '+0,50' => '+0,50',
		            '+0,25' => '+0,25',
		            'PLAN' => 'PLAN',
		            '-0,25' => '-0,25',
		            '-0,50' => '-0,50',
		            '-0,75' => '-0,75',
		            '-1,00' => '-1,00',
		            '-1,25' => '-1,25',
		            '-1,50' => '-1,50',
		            '-1,75' => '-1,75',
		            '-2,00' => '-2,00',
		            '-2,25' => '-2,25',
		            '-2,50' => '-2,50',
	            ],
	            'label' => 'OD Sphère'
            ])
            ->add('ogCyl', ChoiceType::class,[
	            'attr'=>['class'=>'form-control select2'],
	            'choices' =>[
		            '+2,50' => '+2,50',
		            '+2,25' => '+2,25',
		            '+2,00' => '+2,00',
		            '+1,75' => '+1,75',
		            '+1,50' => '+1,50',
		            '+1,25' => '+1,25',
		            '+1,00' => '+1,00',
		            '+0,75' => '+0,75',
		            '+0,50' => '+0,50',
		            '+0,25' => '+0,25',
		            'PLAN' => 'PLAN',
		            '-0,25' => '-0,25',
		            '-0,50' => '-0,50',
		            '-0,75' => '-0,75',
		            '-1,00' => '-1,00',
		            '-1,25' => '-1,25',
		            '-1,50' => '-1,50',
		            '-1,75' => '-1,75',
		            '-2,00' => '-2,00',
		            '-2,25' => '-2,25',
		            '-2,50' => '-2,50',
	            ]
            ])
            ->add('ogAxe', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OG Axe',
	            'required' => false
            ])
            ->add('ogAdd', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OG Add',
	            'required' => false
            ])
            ->add('ogQte', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OG Quanite',
	            'required' => false
            ])
            ->add('ogMontant', TextType::class,[
				'attr' => ['class' => 'form-control'],
	            'label' => 'OG Montant',
	            'required' => true
            ])
            ->add('ogSph', ChoiceType::class,[
	            'attr'=>['class'=>'form-control select2'],
	            'choices' =>[
		            '+2,50' => '+2,50',
		            '+2,25' => '+2,25',
		            '+2,00' => '+2,00',
		            '+1,75' => '+1,75',
		            '+1,50' => '+1,50',
		            '+1,25' => '+1,25',
		            '+1,00' => '+1,00',
		            '+0,75' => '+0,75',
		            '+0,50' => '+0,50',
		            '+0,25' => '+0,25',
		            'PLAN' => 'PLAN',
		            '-0,25' => '-0,25',
		            '-0,50' => '-0,50',
		            '-0,75' => '-0,75',
		            '-1,00' => '-1,00',
		            '-1,25' => '-1,25',
		            '-1,50' => '-1,50',
		            '-1,75' => '-1,75',
		            '-2,00' => '-2,00',
		            '-2,25' => '-2,25',
		            '-2,50' => '-2,50',
	            ]
            ])
            //->add('prixMonture')
            //->add('statut')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('montureBool')
            //->add('verreBool')
            //->add('client')
            //->add('monture')
            ->add('typeVerre', EntityType::class,[
				'attr' => ['class' => 'form-control select2'],
	            'class' => TypeVerre::class,
	            'query_builder' => function(EntityRepository $entityRepository){
					return $entityRepository->createQueryBuilder('v')
						->orderBy('v.libelle', "ASC");
	            },
	            "choice_label" => function($typeVerre){
					return $typeVerre->getLibelle();
	            }
            ])
            //->add('assurance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}

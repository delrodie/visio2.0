<?php

namespace App\Form;

use App\Entity\TypeVerre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeVerreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class,[
	            'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
	            'label' => "Nom du type de verre"
            ])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeVerre::class,
        ]);
    }
}

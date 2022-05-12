<?php

namespace App\Form;

use App\Entity\Versement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('montant', IntegerType::class,[])
            ->add('acompte', IntegerType::class,['attr'=>['class'=>"form-control", 'autocomplete'=>"off"]])
            //->add('reste', IntegerType::class,['attr'=>['class'=>'form-control', 'readonly'=>true]])
            ->add('date', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off"]])
            //->add('createdAt')
            //->add('facture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
        ]);
    }
}

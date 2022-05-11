<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'], 'label'=>"Nom de l'entreprise *"])
            ->add('adresse', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'], 'label'=>"Adresse postale", 'required'=>false])
            ->add('localisation', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'], 'label'=>"Localisation *"])
            ->add('tel', TelType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'], 'label'=>"Telephone", 'required'=>false])
            ->add('cel', TelType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'], 'label'=>"Cellulaire *"])
            ->add('media', FileType::class,[
	            'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
	            'label' => "Télécharger le logo",
	            'mapped' => false,
	            'multiple' => false,
	            'constraints' => [
		            new File([
			            'maxSize' => "1000000k",
			            'mimeTypes' =>[
				            'image/png',
				            'image/jpeg',
				            'image/jpg',
				            'image/gif',
				            'image/webp',
			            ],
			            'mimeTypesMessage' => "Votre fichier doit être de type image"
		            ])
	            ],
	            'required' => false
            ])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}

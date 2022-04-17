<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Monture;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureMontureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
	    //$this->client = $options['client'];
	    //$slug = $this->client;
		
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
            //->add('odcyl')
            //->add('odAxe')
            //->add('odAdd')
            //->add('odQte')
            //->add('odMontant')
            //->add('odSph')
            //->add('ogCyl')
            //->add('ogAxe')
            //->add('ogAdd')
            //->add('ogQte')
            //->add('ogMontant')
            //->add('ogSph')
            ->add('prixMonture', TextType::class, [
				'attr'=>['class'=>'form-control', 'autocomplete'=>'off']
            ])
            //->add('statut')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('montureBool')
            //->add('verreBool')
            //->add('client')
            ->add('monture', EntityType::class,[
				'attr'=>['class'=>'form-control select2'],
	            'class' => Monture::class,
	            'query_builder' => function(EntityRepository $entityRepository){
					return $entityRepository->createQueryBuilder('m')
						->where('m.stock > 0')
						->orderBy('m.reference', "ASC")
						;
	            },
	            'choice_label' => function($monture){
					return $monture->getMarque()->getNom().' - '.$monture->getReference();
	            },
	            'label' => "Monture *"
            ])
            //->add('typeVerre')
            //->add('assurance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
	        //'client' => null
        ]);
    }
}

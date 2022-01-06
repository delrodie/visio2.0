<?php

namespace App\Form;

use App\Entity\Assurance;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Monture;
use App\Entity\TypeVerre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
	    $this->client = $options['client'];
	    $slug = $this->client;
		
        $builder
            //->add('numero')
            //->add('montantHt')
            //->add('remise')
            //->add('tva')
            //->add('montantTTC')
            //->add('accompte')
            //->add('rap')
            //->add('partAssurance')
            ->add('date')
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
            //->add('prixMonture')
            //->add('statut')
            ->add('montureBool', ChoiceType::class)
            ->add('verreBool',ChoiceType::class,[])
            ->add('client', EntityType::class,[
	            'attr' => ['class'=>"form-control"],
				'class' => Client::class,
	            'query_builder' => function(EntityRepository $entityRepository) use($slug){
					return $entityRepository->createQueryBuilder('c')
						->where('c.slug = :slug')
						->setParameter('slug', $slug)
						;
	            },
	            'choice_label' => function($client){
					return $client->getNom().' '.$client->getPrenoms();
	            },
	            'label' => "Client *"
            ])
            ->add('assurance', EntityType::class,[
	            'attr' => ['class'=>"form-control select"],
				'class' => Assurance::class,
	            'query_builder' => function(EntityRepository $entityRepository){
					return $entityRepository->createQueryBuilder('a')
						->orderBy('a.nom', "ASC");
	            },
	            'choice_label' => 'nom',
	            'label' => "Assurance ",
	            'help' => "Facultatif",
	            'help_attr' => ['style'=>"font-style: italic; color:red; font-size:.85rem;"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
	        'client' => null
        ]);
    }
}

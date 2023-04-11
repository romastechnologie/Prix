<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class,[
                'class'=>Client::class,
                'label_html'=>true,
                'required'=>true,
                'label'=>'Client <span style="color: red;"><strong>*</strong></span>',
                'placeholder'=>'Selectionner un client',
                'attr'=>[
                    "class"=>"form-control selectpicker",
                    'data-live-search'=>true,
                ]
            ])
            ->add('produit', EntityType::class,[
                'label_html'=>true,
                'class'=>Produit::class,
                'required'=>true,
                'placeholder'=>'Selectionner un produit ',
                'label'=>'Produit <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    "class"=>"form-control selectpicker",
                    'data-live-search'=>true,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

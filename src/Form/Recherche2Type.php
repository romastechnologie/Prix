<?php

namespace App\Form;

use App\Entity\CategClient;
use App\Entity\Produit;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Recherche2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categ', EntityType::class,[
                'class'=>CategClient::class,
                'label_html'=>true,
                'required'=>true,
                'label'=>'Catégorie de client <span style="color: red;"><strong>*</strong></span>',
                'placeholder'=>'Selectionner une catégorie de client',
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

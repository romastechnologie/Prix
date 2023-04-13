<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\PrixConditionnementType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('designation', TextType::class,[
            'label_html' => true,
            'mapped'=>true,
            'required'=>true,
            'label'=>'Désignation <span style="color: red;"><strong>*</strong></span>',
            'attr'=>[
                'readonly'=>true,
                'class'=>'form-control'
            ]
        ])
        ->add('refUsine', TextType::class,[
            'required'=>false,
            'mapped'=>true,
            'label'=>'Ref Usine ',
            'attr'=>[
                'readonly'=>true,
                'class'=>'form-control'
            ]
        
        ])
            ->add('conditionners', CollectionType::class,[
                'mapped'=>true,
                'required'=>true,
                'entry_type'=>PrixConditionnementType::class,
                'by_reference'=>false,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype_name'=> '_name_'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Conditionnement;
use App\Entity\Conditionner;
use App\Entity\Prix;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixContionnerAchatRevientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('conditionner',EntityType::class,[
            'label_html' => true,
            'required'=>true,
            'placeholder'=>'Selectionner un conditionnement',
            'class'=>Conditionner::class,
            'label'=>'Conditionnement',
            'attr'=>[
                'class'=>' form-control',
                'data-live-search'=>true,
                'data-select2-id'=>'kt_select2_1'
            ]
        ])
        ->add('NumberType',TextType::class,[
            'label_html' => true,
            'required'=>true,
            'label'=>"Prix de vente Habituel <span style='color: red;'><strong>*</strong></span>",
            'attr'=>[
                'class'=>'form-control'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prix::class,
        ]);
    }
}

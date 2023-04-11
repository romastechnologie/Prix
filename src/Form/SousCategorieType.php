<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class,[
                'label'=>"Id"
            ])
            ->add('code',TextType::class,[
                'label_html' => true,
                'label'=>'Code <span style="color: red;"><strong>*</strong></span>',
                'required'=>false,
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('libelle',TextType::class,[
                'label_html' => true,
                'label'=>'Libellé <span style="color: red;"><strong>*</strong></span>',
                'required'=>false,
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('categorie',EntityType::class,[
                'label_html' => true,
                'class'=>Categorie::class,
                'label'=>'Catégorie <span style="color: red;"><strong>*</strong></span>',
                'placeholder'=>'Selectionner une catégorie',
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control selectpicker',
                    'data-live-search'=>true,
                    'placeholder'=>'Selectionner une catégorie'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousCategorie::class,
        ]);
    }
}

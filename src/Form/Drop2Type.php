<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Drop2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("sel",ChoiceType::class,[
                'label'=>"Type",
                'choices'   => array(
                    'Catégorie'   => 'Catégorie',
                    'Sous Catégorie' => 'Sous Catégorie',
                    'Conditionnement'   => 'Conditionnement',
                    'Produit'   => 'Produit',
                ),
                'placeholder' => 'Selectionner un type de fichier',
                'attr'=>[
                    'class'=>'form-group selectpicker',
                    'data-live-search'=>true,
                    'placeholder' => 'Selectionner un type de fichier',
                    ]
            ])
            ->add('dropFile',DropzoneType::class,[
            'label_html' => true,
            'label'=>'Fichier CSV <span style="color: red;"><strong>*</strong></span>',
            'allow_file_upload'=> [
                "application/csv"
            ],
            'attr'=>[
                'class'=>'form-group dropzone-filename dropzone-primary',
                'placeholder' => 'Glissez et déposez',
                //'style'=>" height: 200px; border-radius: 5px; display:none; justify-content: center; width : 100%; border : dashed 3px ; border-color: #3699ff;"
                'style'=>""
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

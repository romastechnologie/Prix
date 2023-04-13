<?php

namespace App\Form;

use App\Entity\ConditionnerCateClient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use App\Entity\CategClient;
use App\Entity\Conditionnement;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixClientCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prixMin',NumberType::class,[
            'label_html' => true,
            'required'=>false,
            'label'=>'Prix Min',
            'attr'=>[
                'data-verif'=>"nonOk",
                'class'=>'form-control tape'
            ]
        ])
        ->add('prixMax', NumberType::class,[
            'label_html' => true,
            'required'=>false,
            'label'=>'Prix Max',
            'attr'=>[
                'data-verif'=>"nonOk",
                'class'=>'form-control tape'
            ]
        
        ])
        ->add('prixVente', NumberType::class,[
            'label_html' => true,
            'required'=>true,
            'label'=>'Prix de vente <span style="color: red;"><strong>*</strong></span>',
            'attr'=>[
                'data-verif'=>"nonOk",
                'class'=>'form-control tape'
            ]
        
        ])
        ->add('cateClient',EntityType::class,[
            'class'=>CategClient::class,
            'required'=>true,
            'label_html' => true,
            'mapped'=>true,
            'placeholder'=>'Selectionner une sous catégorie',
            'label'=>'Catégorie de Client <span style="color: red;"><strong>*</strong></span>',
            'attr'=>[
                'class'=>'form-control',
                'data-live-search'=>true
            ]
        ])
    //      ->add('conditionnement',EntityType::class,[
        //     'class'=>Conditionnement::class,
        //     'required'=>true,
        //     'label_html' => true,
        //     'mapped'=>false,
        //     'placeholder'=>'Conditionnement',
        //     'label'=>'Conditionnement <span style="color: red;"><strong>*</strong></span>',
        //     'attr'=>[
        //         'class'=>'form-control',
        //         'data-live-search'=>true
        //     ]
        // ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $data = $event->getData();
            $form = $event->getForm();
            if($data && $data->getId() != null){
                $form->remove("cateClient");
                $form ->add('cateClient',EntityType::class,[
                    'class'=>CategClient::class,
                    'required'=>true,
                    'label_html' => true,
                    'mapped'=>true,
                    'placeholder'=>'Selectionner une sous catégorie',
                    'label'=>'Catégorie de Client <span style="color: red;"><strong>*</strong></span>',
                    'attr'=>[
                        'class'=>'form-control',
                        "disabled"=>true,
                        'data-live-search'=>true
                    ]
                    ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConditionnerCateClient::class,
        ]);
    }
}

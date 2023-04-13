<?php

namespace App\Form;

use App\Entity\Prix;
use App\Entity\Conditionner;
use App\Entity\Conditionnement;
use App\Repository\ConditionnementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PrixConditionnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('conditionnement',EntityType::class,[
                'label_html' => true,
                'required'=> true,
                'placeholder'=>'Selectionner un conditionnement',
                'class'=>Conditionnement::class,
                'choice_attr' => function(Conditionnement $cond){
                    return [
                        
                        'data-qte' => $cond->getQte(),
                    ];
                },
                'label'=>'Conditionnement  <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    'class'=>' form-control',
                    'data-live-search'=>true,
                    'data-select2-id'=>'kt_select2_1'
                ]
            ])
            ->add('prixMin',NumberType::class,[
                'label_html' => true,
                'required'=>false,
                'label'=>'Prix Min ',
                'attr'=>[
                    
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            ])
            ->add('prixVente',NumberType::class,[
                'label_html' => true,
                'required'=>true,
                'label'=>'Prix de Vente <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            ])
            ->add('qteProduit',NumberType::class,[
                'label_html' => true,
                'required'=>true,
                'label'=>'Quantité du produit <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            ])
            ->add('prixMax', NumberType::class,[
                'label_html' => true,
                'required'=>false,
                'label'=>'Prix Max ',
                'attr'=>[
                    
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            
            ])
            ->add('conditionnerCateClients', CollectionType::class,[
                'mapped'=>true,
                'required'=>false,
                'entry_type'=>PrixClientCategorieType::class,
                'by_reference'=>false,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype_name'=> '_subname_'
            ])
            ->add('prixConcurentiel', TextType::class,[
                'label_html' => true,
                'required'=>false,
                'label'=>'Prix Concurentiel ',
                'attr'=>[
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            
            ])
            ->add('prixAchat',NumberType::class,[
                'label_html' => true,
                'required'=>false,
                'label'=>"Prix d'achat",
                'attr'=>[
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            ])
            ->add('prixRevient', NumberType::class,[
                'label_html' => true,
                'required'=>false,
                'label'=>"Prix de revient",
                'attr'=>[
                    'data-verif'=>"nonOk",
                    'class'=>'form-control tape'
                ]
            
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $data = $event->getData();
            $form = $event->getForm();
            if (!($data === null) && $data->getId() != "") {
                $form->remove('conditionnement');
                $form->remove('qteProduit');
                $form
                ->add('conditionnement',EntityType::class,[
                    'label_html' => true,
                    'required'=> true,
                    'placeholder'=>'Selectionner un conditionnement',
                    'class'=>Conditionnement::class,
                    'choice_attr' => function(Conditionnement $cond){
                        return [ 'data-qte' => $cond->getQte(), ];
                    },
                    'label'=>'Conditionnement  <span style="color: red;"><strong>*</strong></span>',
                    'attr'=>[
                        'class'=>' form-control',
                        'data-live-search'=>true,
                        'data-select2-id'=>'kt_select2_1',
                        "readonly"=>true,
                    ]
                    ])
                    ->add('qteProduit',NumberType::class,[
                        'label_html' => true,
                        'required'=>true,
                        'label'=>'Quantité du produit <span style="color: red;"><strong>*</strong></span>',
                        'attr'=>[
                            'data-verif'=>"nonOk",
                            "readonly"=>true,
                            'class'=>'form-control tape'
                        ]
                    ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conditionner::class,
        ]);
    }
}

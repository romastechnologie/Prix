<?php

namespace App\Form;

use App\Entity\Conditionner;
use App\Entity\ModeDef;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Form\MediaType;
use App\Form\PrixConditionnementType;
use App\Form\PrixContionnerAchatRevientType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ConditionnerType;
use App\Form\PrixType;
use App\Repository\ConditionnerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{

    private $em;
    private $conR;
    public function __construct(EntityManagerInterface $entityManager, ConditionnerRepository $condRe)
    {
       $this->em = $entityManager;
       $this->conR = $condRe;
    }
    

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class,[
                'label_html' => true,
                'required'=>true,
                'label'=>'Désignation <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('refUsine', TextType::class,[
                'required'=>false,
                'label'=>'Ref Usine ',
                'attr'=>[
                    'class'=>'form-control'
                ]
            
            ])
            ->add('media', CollectionType::class,[
                'entry_type'=>MediaType::class,
                'by_reference'=>false,
                'allow_add'=>true,
                'allow_delete'=>true,
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
            
            ->add('aTaxe', CheckboxType::class,[
                'label'=>'Est Taxable ',
                'required'=>false,
                'attr'=>[
                    'class'=>'checkbox'
                ]
            ])
            ->add('sousCategorie', EntityType::class, [
                'class'=> SousCategorie::class,
                'required'=>true,
                'label_html' => true,
                'placeholder'=>'Selectionner une sous catégorie',
                'label'=>'Sous categorie <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    'class'=>'form-control selectpicker',
                    'data-live-search'=>true
                ]
            ])
            ->add('mode',EntityType::class,[
                'required'=>true,
                'class'=> ModeDef::class,
                'label_html' => true,
                'placeholder'=>'Selectionner un mode',
                'label'=>'Mode DEF <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    'class'=>'form-control selectpicker',
                    'data-live-search'=>true
                ]
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();
            if (!($data === null) && $data->getId() != "") {
                $form->remove('aTaxe');
                $form->add('aTaxe', CheckboxType::class,[
                    'label'=>'Est Taxable ',
                    'required'=>false,
                    'data'=> $data->getATaxe() == 1 ? true : false ,
                    'attr'=>[
                        
                        'class'=>'checkbox'
                    ]
                ])
                ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

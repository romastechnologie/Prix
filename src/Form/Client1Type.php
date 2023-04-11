<?php

namespace App\Form;

use App\Entity\CategClient;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Client1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cateClient', EntityType::class, [
                'class'=> CategClient :: class,
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Catégorie',
                'required' => false,
                'placeholder' => 'Choisissez votre Catégorie',
                'attr' => ['required'=>false, 
                'class'=>'form-control selectpicker', 
                'placeholder' => 'Choisissez votre Catégorie'],
            ])
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Nom',
                'required' => false,
                'attr' => ['required'=>false, 
                'class'=>'form-control mb-2 physique', 
                'placeholder' => 'Entrer le nom'],
            ])
            ->add('prenom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Prénoms',
                'required' => false,
                'attr' => ['required'=>false, 
                'class'=>'form-control mb-2 physique', 
                'placeholder' => 'Entrer le(s) prenom(s)'],
            ])
            ->add('adresse', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Adresse',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'adresse'],
            ])
            ->add('email', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'email'],
            ])
            ->add('telephone1', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Téléphone 1',
                'required' => true,
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le numero de téléphone'],
            ])
            ->add('telephone2', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Téléphone 2',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le numero de téléphone'],
            ])
            ->add('raisonSociale', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Raison sociale',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer la raison sociale'],
            ])
            ->add('ifu', IntegerType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'N° IFU',
                'required' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le numero IFU'],
            ])
            ->add('rccm', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'N° RCCM',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer le numero du Registre de commerce'],
            ])
            ->add('sigle', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Sigle',
                'required' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer le sigle'],
            ])
            ->add('denomination', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Dénomination',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer la denomination'],
            ])
            ->add('statut', ChoiceType::class, [
                'label_attr' => ['class' => 'form-label ok'],
                'choices'  => [
                    'Physique' => 'Physique',
                    'Moral' => 'Moral',
                ],
                'choice_attr' => [
                    'Physique' => ['checked' => true],
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['required'=>true],
            ])
            ->add('dateNais', BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Date de naissance',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 physique', 
                'placeholder' => 'Date de naissance'],
            ])
            // ->add('prixs', CollectionType::class,[
            //     'mapped'=>true,
            //     'required'=>true,
            //     'entry_type'=>PrixContionnerAchatRevientType::class,
            //     'by_reference'=>false,
            //     'allow_add'=>true,
            //     'allow_delete'=>true,
            //     'prototype_name'=> '_name_'
            // ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();
            if (!($data === null) && $data->getId() != "") {
                $form->remove('statut');
                if($data->getStatut() === 'Physique'){ 
                    $form->add('statut', ChoiceType::class, [
                        'label_attr' => ['class' => 'form-label ok'],
                        'choices'  => [
                            'Physique' => 'Physique',
                            'Moral' => 'Moral',
                        ],
                        'choice_attr' => [
                            'Physique' => ['checked' => true],
                        ],
                        'expanded' => true,
                        'multiple' => false,
                        'attr' => ['required'=>true],
                        ])
                        ;
                }else{
                    $form->add('statut', ChoiceType::class, [
                        'label_attr' => ['class' => 'form-label ok'],
                        'choices'  => [
                            'Physique' => 'Physique',
                            'Moral' => 'Moral',
                        ],
                        'choice_attr' => [
                            'Moral' => ['checked' => true],
                        ],
                        'expanded' => true,
                        'multiple' => false,
                        'attr' => ['required'=>true],
                        ])
    
                        ;
                }
            }
        });
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}

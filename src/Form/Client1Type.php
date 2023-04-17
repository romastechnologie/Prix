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
                'label_html' => true,
                'label' => 'Catégorie <span style="color: red;"><strong>*</strong></span>',
                'required' => true,
                'placeholder' => 'Choisissez votre Catégorie',
                'attr' => ['required'=>false, 
                'class'=>'form-control selectpicker', 
                'placeholder' => 'Choisissez votre Catégorie'],
            ])
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Nom <span style="color: red;"><strong>*</strong></span>',
                'label_html' => true,
                'required' => false,
                'attr' => ['required'=>false, 
                'class'=>'form-control mb-2 physique', 
                'placeholder' => 'Entrer le nom'],
            ])
            ->add('prenom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Prénoms <span style="color: red;"><strong>*</strong></span> ',
                'label_html' => true,
                'required' => false,
                'attr' => ['required'=>false, 
                'class'=>'form-control mb-2 physique', 
                'placeholder' => 'Entrer le(s) prenom(s)'],
            ])
            ->add('adresse', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Adresse',
                'required' => false,
                'label_html' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'adresse'],
            ])
            ->add('email', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
                'required' => false,
                'label_html' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'email'],
            ])
            ->add('telephone1', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Téléphone 1 <span style="color: red;"><strong>*</strong></span>',
                'required' => true,
                'label_html' => true,
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le numero de téléphone'],
            ])
            ->add('telephone2', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Téléphone 2',
                'required' => false,
                'label_html' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le numero de téléphone'],
            ])
            ->add('raisonSociale', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Raison sociale <span style="color: red;"><strong>*</strong></span>',
                'required' => false,
                'label_html' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer la raison sociale'],
            ])
            ->add('ifu', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'N° IFU <span id="fui"></span>',
                'required' => false,
                'label_html' => true,
                'attr' => ['required'=>false,
                "maxlength"=>"13",
                'class'=>'form-control mb-2', 
                'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');", 
                "maxlength"=>"13",
                'placeholder' => 'Entrer le numero IFU'],
            ])
            ->add('rccm', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'N° RCCM  <span style="color: red;"><strong>*</strong></span>',
                'required' => false,
                'label_html' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer le numero du Registre de commerce'],
            ])
            ->add('sigle', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Sigle <span style="color: red;"><strong>*</strong></span>',
                'required' => true,
                'label_html' => true,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2 moral', 
                'placeholder' => 'Entrer le sigle'],
            ])
            ->add('denomination', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Dénomination <span style="color: red;"><strong>*</strong></span>',
                'required' => false,
                'label_html' => true,
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
                'label_html' => true,
                
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Date de naissance  <span id="etad"></span>',
                'required' => false,
                'attr' => ['required'=>false,
                'placeholder'=>'JJ/MM/YYYY',
                'class'=>'form-control mb-2 physique', 
                'placeholder' => 'Date de naissance'],
            ])
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

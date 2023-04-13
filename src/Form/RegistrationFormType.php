<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File as FileFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                "attr"=>[
                    'placeholder'=>"Email",
                    'class'=>'form-control'
                ]
            ])
            ->add('username',TextType::class,[
                "attr"=>[
                    'placeholder'=>"Pseudo",
                    'class'=>'form-control'
                ]
            ])
            ->add('nom',TextType::class,[
                "attr"=>[
                    'placeholder'=>"Nom",
                    'class'=>'form-control'
                ]
            ])
            ->add('prenom',TextType::class,[
                "attr"=>[
                    'placeholder'=>"Prénom",
                    'class'=>'form-control'
                ]
            ])
            ->add('userRoles',EntityType::class,[
                'label'=>"Droit",
                'multiple'=>true,
                'required'=>true,
                'class'=> Role::class,
                'choice_label' => function ($choice) {
                    return $choice->getRole();
                },
                'placeholder'=> 'Sélectionnez les droits...',
                'attr'=>[
                    'class'=>'form-control selectpicker',
                    'placeholder'=> 'Sélectionnez les droits...',
                    'data-live-search'=>true,
                ],

            ])
            ->add('telephone',TextType::class,[
                'required'=>false,
                "attr"=>[
                    'required'=>false,
                    'placeholder'=>"Téléphone",
                    'class'=>'form-control'
                ]
                ])
                ->add('adresse',TextType::class,[
                    'required'=>false,
                    "attr"=>[
                        'required'=>false,
                        'placeholder'=>"Adresse",
                        'class'=>'form-control'
                    ]
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('file',FileType::class,[
                'label'=>"Ajouter une photo",
                'multiple'=>false,
                'required'=>false,
                'mapped'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                    ],
                'attr'=>[
                    'class'=>"form-control h-auto text-white"
                ]
            ])
            ->add('password', RepeatedType::class, [
                "type"=>PasswordType ::class,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => true,
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'first_options'  => ['label'=>"Mot de passe",'attr' => ['placeholder' => 'Mot de passe','class' => 'form-control password-field']],
                'second_options' => ['label'=>"Confirmation de mot de passe",'attr' => ['placeholder' => 'Confirmation de mot de passe','class' => 'form-control password-field']],
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insérer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit être de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

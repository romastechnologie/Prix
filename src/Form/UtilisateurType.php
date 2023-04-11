<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email',EmailType::class,[
            'required'=>true,
            "attr"=>[
                'placeholder'=>"Email",
                'class'=>'form-control '
            ]
        ])
        ->add('username',TextType::class,[
            'required'=>true,
            "attr"=>[
                'placeholder'=>"Pseudo",
                'class'=>'form-control '
            ]
        ])
        ->add('nom',TextType::class,[
            'required'=>true,
            "attr"=>[
                'placeholder'=>"Nom",
                'class'=>'form-control '
            ]
        ])
        ->add('prenom',TextType::class,[
            'required'=>true,
            "attr"=>[
                'placeholder'=>"Prénom",
                'class'=>'form-control '
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
                'class'=>'form-control  select2-selection--single',
                'data-live-search'=>true,
            ],

        ])
        ->add('telephone',TextType::class,[
            'required'=>false,
            "attr"=>[
                'placeholder'=>"Téléphone",
                'class'=>'form-control '
            ]
            ])
            ->add('adresse',TextType::class,[
                'required'=>false,
                "attr"=>[
                    'placeholder'=>"Adresse",
                    'class'=>'form-control '
                ]
            ])
        ->add('file',FileType::class,[
            'required'=>false,
            'label'=>"Photo",
            'multiple'=>false,
            'required'=>false,
            'mapped'=>false,
            'attr'=>[
                'class'=>"form-control "
            ]
        ])
        ->add('password', RepeatedType::class, [
            "type"=>PasswordType ::class,
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => true,
            'required'=>true,
            'options' => ['attr' => ['class' => 'form-control password-field ']],
            'first_options'  => ['label'=>false,'attr' => ['placeholder' => 'Mot de passe','class' => 'form-control password-field ']],
            'second_options' => ['label'=>false,'attr' => ['placeholder' => 'Confirmation de mot de passe','class' => 'form-control password-field']],
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

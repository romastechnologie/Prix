<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterMdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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

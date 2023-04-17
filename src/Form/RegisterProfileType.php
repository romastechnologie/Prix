<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterProfileType extends AbstractType
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
            "required"=>false,
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
        ->add('telephone',TextType::class,[
            "attr"=>[
                'class'=>'form-control',
                'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');", 
                "maxlength"=>"13",
                'placeholder' => '00229XXXXXXXX',
            ]
        ])
        ->add('adresse',TextType::class,[
            "required"=>false,
            "attr"=>[
                'placeholder'=>"Adresse",
                'class'=>'form-control'
            ]
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

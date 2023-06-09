<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class,[
                'label'=>"Id"
            ])
            ->add('code', TextType::class,[
                'label_html' => true,
                'label'=>'Code <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    "onkeyup"=>"this.value = this.value.toUpperCase();",
                    "placeholder"=>"CARE",
                    'class'=>'form-control',
                    'maxlength'=>"4"
                ]
            
            ])
            ->add('libelle', TextType::class,[
                'label_html' => true,
                'label'=>'Libellé <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    "placeholder"=>"CARRELAGE",
                    "onkeyup"=>"this.value = this.value.toUpperCase();",
                    'class'=>'form-control'
                ]
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}

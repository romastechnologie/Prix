<?php

namespace App\Form;

use App\Entity\CategClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CateClientType extends AbstractType
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
                'class'=>'form-control',
                "onkeyup"=>"this.value = this.value.toUpperCase();",
                'maxlength'=>"4"
                ]
            ])
        ->add('libelle', TextType::class,[
            'label_html' => true,
            'label'=>'Libellé <span style="color: red;"><strong>*</strong></span>',
            'attr'=>[
                "onkeyup"=>"this.value = this.value.toUpperCase();",
                'class'=>'form-control']
            ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategClient::class,
        ]);
    }
}

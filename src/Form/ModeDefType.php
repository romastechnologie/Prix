<?php

namespace App\Form;

use App\Entity\ModeDef;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeDefType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class,[
                'label'=>"Id"
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
            'data_class' => ModeDef::class,
        ]);
    }
}

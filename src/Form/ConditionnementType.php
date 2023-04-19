<?php

namespace App\Form;

use App\Entity\Conditionnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionnementType extends AbstractType
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
                    'maxlength'=>"4",
                    "placeholder"=>"UNIT",
                    "onkeyup"=>"this.value = this.value.toUpperCase();",
                    'class'=>'form-control']
                ])
            ->add('libelle', TextType::class,[
                'label_html' => true,
                'label'=>'Libellé <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    "placeholder"=>"UNITE",
                    "onkeyup"=>"this.value = this.value.toUpperCase();",
                    'class'=>'form-control']
                ])
            ->add('qte', TextType::class,[
                'label_html' => true,
                'label'=>'Quantité <span style="color: red;"><strong>*</strong></span>',
                'attr'=>[
                    "placeholder"=>"1",
                    "onkeyup"=>"this.value = this.value.replace(/[^0-9\.]/g,''); let va = parseFloat($(this).val()); if(va == 0 || isNaN(va) ){ $(this).val(''); }else{ $(this).val(new Intl.NumberFormat('fr-FR').format(va)) }",
                    'class'=>'form-control']
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conditionnement::class,
        ]);
    }
}

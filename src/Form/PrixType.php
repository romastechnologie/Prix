<?php

namespace App\Form;

use App\Entity\Prix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prixMin')
            ->add('prixMax')
            ->add('dateAttribution')
            ->add('dateFin')
            ->add('estActif')
            ->add('prixConcurentiel')
            ->add('conditionnement')
            ->add('conditionnerClient')
            ->add('conditionner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prix::class,
        ]);
    }
}

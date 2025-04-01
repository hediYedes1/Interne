<?php

namespace App\Form;

use App\Entity\Interview;
use App\Entity\Testtechnique;
use App\Enum\StatutTestTechnique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;



class TesttechniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titretesttechnique')
            ->add('descriptiontesttechnique')
            ->add('dureetesttechnique')
            ->add('statuttesttechnique', EnumType::class, [
                'class' => StatutTestTechnique::class,
                'choice_label' => fn(StatutTestTechnique $type) => $type->getLabel(),
                'label' => 'Type de test technique',
                'placeholder' => 'Sélectionnez un type',
            ])
            ->add('datecreationtesttechnique', null, [
                'widget' => 'single_text',
            ])
            ->add('questions')
            ->add('idinterview', EntityType::class, [
                'class' => Interview::class,
                'choice_label' => 'idinterview',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testtechnique::class,
        ]);
    }
}
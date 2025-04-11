<?php

namespace App\Form;

use App\Entity\Affectationhebergement;
use App\Entity\Hebergement;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Affectationhebergement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datedebut', null, [
                'widget' => 'single_text',
            ])
            ->add('datefin', null, [
                'widget' => 'single_text',
            ])
            ->add('idhebergement', EntityType::class, [
                'class' => Hebergement::class,
                'choice_label' => 'nomhebergement',
            ])
            ->add('idutilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nomutilisateur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectationhebergement::class,
            'attr' => [
                'novalidate' => 'novalidate' // Disable HTML5 validation
            ]
        ]);
    }
}

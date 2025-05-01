<?php

namespace App\Form;

use App\Entity\Interview;
use App\Entity\Offre;
use App\Enum\TypeInterview;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class InterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idoffre', EntityType::class, [
                'class' => Offre::class,
                'choice_label' => 'titreoffre',
                'label' => 'Offre',
                'placeholder' => 'Sélectionnez une offre',
            ])
            ->add('dateinterview', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'interview',
            ])
            ->add('typeinterview', EnumType::class, [
                'class' => TypeInterview::class,
                'choice_label' => fn(TypeInterview $type) => $type->getLabel(),
                'label' => 'Type d\'interview',
                'placeholder' => 'Sélectionnez un type',

            ])
            ->add('localisation', null, [
            'label' => 'Lieu',
            'required' => false, // Car ce champ n'est pas obligatoire pour les interviews en ligne
])
            ->add('timeinterview', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de l\'interview',
                'input' => 'datetime',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
        ]);
    }
}
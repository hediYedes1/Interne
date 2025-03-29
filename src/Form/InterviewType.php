<?php

namespace App\Form;

use App\Entity\Interview;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreoffre', EntityType::class, [
                'class' => Offre::class,
                'choice_label' => 'titre', // Supposant que votre entité Offre a un champ 'titre'
                'label' => 'Offre',
                'placeholder' => 'Sélectionnez une offre',
            ])
            ->add('dateinterview', null, [
                'widget' => 'single_text',
                'label' => 'Date de l\'interview',
            ])
            ->add('typeinterview', ChoiceType::class, [
                'choices' => [
                    // Remplacez ces valeurs par celles de votre énumération
                    'Entretien technique' => 'technique',
                    'Entretien RH' => 'rh',
                    'Entretien managérial' => 'managerial',
                ],
                'label' => 'Type d\'interview',
                'placeholder' => 'Sélectionnez un type',
            ])
            ->add('lienmeet', null, [
                'label' => 'Lien Meet',
                'required' => false, // Si le champ n'est pas obligatoire
            ])
            ->add('localisation', null, [
                'label' => 'Lieu',
            ])
            ->add('timeinterview', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de l\'interview',
            ])
            // On supprime idoffre car il sera géré automatiquement via la relation
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
        ]);
    }
}
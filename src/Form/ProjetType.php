<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreprojet')
            ->add('descriptionprojet')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'data' => new \DateTime(), // aujourd'hui
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => (new \DateTime())->format('Y-m-d'),
                        'message' => 'La date de début ne peut pas être dans le passé.',
                    ]),
                ],
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'data' => (new \DateTime('+1 day')), // demain
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => (new \DateTime())->format('Y-m-d'),
                        'message' => 'La date de fin ne peut pas être dans le passé.',
                    ]),
                ],
            ])
            ->add('idoffre', EntityType::class, [
                'class' => Offre::class,
                'choice_label' => 'titreoffre',       
                'choice_value' => 'idoffre',         
                'label' => 'Offre associée',
                'placeholder' => 'Sélectionnez une offre'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
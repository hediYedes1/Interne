<?php

namespace App\Form;

use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    DateType,
    TextType,
    TextareaType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{
    NotBlank,
    Length,
    GreaterThanOrEqual,
    Expression
};

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreprojet', TextType::class, [
                'label' => 'Titre du projet*',
                'attr' => [
                    'placeholder' => 'Ex: Migration vers Symfony 6'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire']),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('descriptionprojet', TextareaType::class, [
                'label' => 'Description*',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Décrivez les objectifs du projet...'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire']),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'La description doit contenir au moins {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début*',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d')
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est obligatoire']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de début ne peut pas être dans le passé'
                    ])
                ]
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin*',
                'attr' => [
                    'min' => (new \DateTime('+1 day'))->format('Y-m-d')
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La date de fin est obligatoire']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de fin ne peut pas être dans le passé'
                    ]),
                    new Expression([
                        'expression' => 'value >= this.getParent().get("datedebut").getData()',
                        'message' => 'La date de fin doit être postérieure à la date de début'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
            'attr' => ['novalidate' => 'novalidate'] // Désactive la validation HTML5 pour utiliser Symfony
        ]);
    }
}
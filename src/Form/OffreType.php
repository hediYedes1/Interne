<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Projet;
use App\Enum\TypeContrat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreoffre', TextType::class, [
                'label' => 'Titre de l\'offre*',
                'attr' => ['placeholder' => 'Ex: Développeur Symfony Senior'],
            ])
            ->add('descriptionoffre', TextareaType::class, [
                'label' => 'Description*',
                'attr' => ['rows' => 5, 'placeholder' => 'Décrivez les missions principales...'],
            ])
            ->add('salaireoffre', NumberType::class, [
                'label' => 'Salaire (TND)*',
                'html5' => true,
                'attr' => ['min' => 0, 'step' => 50],
            ])
            ->add('localisationoffre', TextType::class, [
                'label' => 'Localisation*',
                'attr' => ['placeholder' => 'Ex: Tunis, Remote, Hybride...'],
            ])
            ->add('emailrh', EmailType::class, [
                'label' => 'Email RH*',
                'attr' => ['placeholder' => 'rh@entreprise.com'],
            ])
            ->add('typecontrat', ChoiceType::class, [
                'label' => 'Type de contrat*',
                'choices' => [
                    'CDI' => TypeContrat::CDI,
                    'CDD' => TypeContrat::CDD,
                    'Stage' => TypeContrat::STAGE,
                ],
                'choice_label' => fn($choice) => $choice->value,
                'choice_value' => fn(?TypeContrat $enum) => $enum?->value,
                'placeholder' => 'Choisir un type de contrat',
            ])
            ->add('datelimite', DateTimeType::class, [
                'label' => 'Date limite*',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                    'class' => 'datetimepicker' // Add this if using JS datetime picker
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\GreaterThan([
                        'value' => 'now',
                        'message' => 'La date limite doit être dans le futur'
                    ]),
                ],
                // Add these for better time handling
                'input' => 'datetime',
                'with_seconds' => false,
                'minutes' => range(0, 55, 5), // Optional: limit minute selection
            ])
            ->add('projet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'titreprojet',
                'label' => 'Projet associé',
                'placeholder' => 'Sélectionnez un projet',
                'required' => false,
                'choices' => $options['projets'] ?? [],
                'attr' => ['class' => 'select2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
            'projets' => []
        ]);

        $resolver->setAllowedTypes('projets', 'array');
    }
}
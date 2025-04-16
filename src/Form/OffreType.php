<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\{
    DateType,
    TextType,
    NumberType,
    EmailType,
    TextareaType
};
use Symfony\Component\Validator\Constraints\{
    NotBlank,
    Length,
    Positive,
    Email,
    GreaterThan,
    GreaterThanOrEqual
};

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idoffre', HiddenType::class)
            ->add('titreoffre', TextType::class, $this->getTitreOffreOptions())
            ->add('descriptionoffre', TextareaType::class, $this->getDescriptionOptions())
            ->add('salaireoffre', NumberType::class, $this->getSalaireOptions())
            ->add('localisationoffre', TextType::class, $this->getLocalisationOptions())
            ->add('emailrh', EmailType::class, $this->getEmailRhOptions())
            ->add('typecontrat', TextType::class, $this->getTypeContratOptions())
            ->add('datelimite', DateType::class, $this->getDateLimiteOptions())
            ->add('projet', EntityType::class, $this->getProjetOptions($options));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
            'projets' => []
        ]);

        $resolver->setAllowedTypes('projets', 'array');
    }

    private function getTitreOffreOptions(): array
    {
        return [
            'label' => 'Titre de l\'offre*',
            'attr' => ['placeholder' => 'Ex: Développeur Symfony Senior'],
            'constraints' => [
                new NotBlank(['message' => 'Le titre est obligatoire']),
                new Length([
                    'max' => 255,
                    'maxMessage' => 'Le titre ne doit pas dépasser {{ limit }} caractères'
                ])
            ]
        ];
    }

    private function getDescriptionOptions(): array
    {
        return [
            'label' => 'Description*',
            'attr' => ['rows' => 5, 'placeholder' => 'Décrivez les missions principales...'],
            'constraints' => [
                new NotBlank(['message' => 'La description est obligatoire']),
                new Length([
                    'min' => 20,
                    'minMessage' => 'La description doit contenir au moins {{ limit }} caractères'
                ])
            ]
        ];
    }

    private function getSalaireOptions(): array
    {
        return [
            'label' => 'Salaire (TND)*',
            'html5' => true,
            'attr' => ['min' => 0, 'step' => 50],
            'constraints' => [
                new NotBlank(['message' => 'Le salaire est obligatoire']),
                new Positive(['message' => 'Le salaire doit être positif']),
                new GreaterThanOrEqual(['value' => 0, 'message' => 'Le salaire ne peut pas être négatif'])
            ]
        ];
    }

    private function getLocalisationOptions(): array
    {
        return [
            'label' => 'Localisation*',
            'attr' => ['placeholder' => 'Ex: Tunis, Remote, Hybride...'],
            'constraints' => [
                new NotBlank(['message' => 'La localisation est obligatoire'])
            ]
        ];
    }

    private function getEmailRhOptions(): array
    {
        return [
            'label' => 'Email RH*',
            'attr' => ['placeholder' => 'rh@entreprise.com'],
            'constraints' => [
                new NotBlank(['message' => 'L\'email RH est obligatoire']),
                new Email(['message' => 'Veuillez entrer un email valide'])
            ]
        ];
    }

    private function getTypeContratOptions(): array
    {
        return [
            'label' => 'Type de contrat*',
            'attr' => ['placeholder' => 'Ex: CDI, CDD, Stage...'],
            'constraints' => [
                new NotBlank(['message' => 'Le type de contrat est obligatoire'])
            ]
        ];
    }

    private function getDateLimiteOptions(): array
    {
        return [
            'widget' => 'single_text',
            'label' => 'Date limite*',
            'attr' => ['min' => (new \DateTime())->format('Y-m-d')],
            'constraints' => [
                new NotBlank(['message' => 'La date limite est obligatoire']),
                new GreaterThan([
                    'value' => 'today',
                    'message' => 'La date doit être dans le futur'
                ])
            ]
        ];
    }

    private function getProjetOptions(array $options): array
    {
        return [
            'class' => Projet::class,
            'choice_label' => 'titreprojet',
            'label' => 'Projet associé',
            'placeholder' => 'Sélectionnez un projet',
            'required' => false,
            'choices' => $options['projets'] ?? [],
            'attr' => ['class' => 'select2'] // Optionnel pour Select2
        ];
    }
}
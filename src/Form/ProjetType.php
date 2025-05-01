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

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreprojet', TextType::class, $this->getTitreProjetOptions())
            ->add('descriptionprojet', TextareaType::class, $this->getDescriptionProjetOptions())
            ->add('datedebut', DateType::class, $this->getDateDebutOptions())
            ->add('datefin', DateType::class, $this->getDateFinOptions());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }

    private function getTitreProjetOptions(): array
    {
        return [
            'label' => 'Titre du projet*',
            'attr' => [
                'placeholder' => 'Ex: Migration vers Symfony 6'
            ]
        ];
    }

    private function getDescriptionProjetOptions(): array
    {
        return [
            'label' => 'Description*',
            'attr' => [
                'rows' => 5,
                'placeholder' => 'Décrivez les objectifs du projet...'
            ]
        ];
    }

    private function getDateDebutOptions(): array
    {
        return [
            'widget' => 'single_text',
            'label' => 'Date de début*',
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d')
            ]
        ];
    }

    private function getDateFinOptions(): array
    {
        return [
            'widget' => 'single_text',
            'label' => 'Date de fin*',
            'attr' => [
                'min' => (new \DateTime('+1 day'))->format('Y-m-d')
            ]
        ];
    }
}

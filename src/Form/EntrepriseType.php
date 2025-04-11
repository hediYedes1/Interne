<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import FileType
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('nomentreprise', null, [
                'label' => 'Nom de l\'entreprise',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('descriptionentreprise', null, [
                'label' => 'Description de l\'entreprise',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Décrivez l\'entreprise'],
            ])
            ->add('logoentreprise', FileType::class, [
                'label' => 'Logo de l\'entreprise (facultatif)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'mapped' => false, // Important: handle file upload separately
            ])
            ->add('urlentreprise', null, [
                'label' => 'Site Web de l\'entreprise',
                'attr' => ['class' => 'form-control', 'placeholder' => 'https://www.example.com'],
            ])
            ->add('secteurentreprise', null, [
                'label' => 'Secteur de l\'entreprise',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}

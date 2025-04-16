<?php

namespace App\Form;

use App\Entity\Brancheentreprise;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrancheentrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('localisationbranche')
            ->add('emailbranche')
            ->add('contactbranche')
            ->add('nombreemploye')
            ->add('responsablebranche')
        
            ->add('identreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomentreprise', // Remplacez par le champ à afficher dans le menu déroulant
                'label' => 'Entreprise',
                'placeholder' => 'Sélectionnez une entreprise',
                'required' => true,
            ])
        
            ->add('idutilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'idutilisateur',
                'label' => 'Utilisateur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brancheentreprise::class,
        ]);
    }
}
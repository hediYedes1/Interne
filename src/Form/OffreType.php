<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Offre;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idoffre', null, [
                'disabled' => true, // Optionnel : si tu ne veux pas que l'utilisateur puisse modifier l'ID
            ])
            ->add('titreoffre')
            ->add('descriptionoffre')
            ->add('salaireoffre')
            ->add('localisationoffre')
            ->add('emailrh')
            ->add('typecontrat')
            ->add('datelimite', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('idutilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'idutilisateur',
            ])
            ->add('identreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'identreprise',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}

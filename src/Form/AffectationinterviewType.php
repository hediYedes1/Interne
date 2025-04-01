<?php

namespace App\Form;

use App\Entity\Affectationinterview;
use App\Entity\Interview;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AffectationinterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idutilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function(Utilisateur $utilisateur) {
                    return $utilisateur->getNomutilisateur() . ' ' . $utilisateur->getPrenomutilisateur();
                },
                'label' => 'Utilisateur',
                'placeholder' => 'Sélectionnez un utilisateur',
                'attr' => ['class' => 'select2'] // Pour Select2 si vous l'utilisez
            ])
            ->add('dateaffectationinterview', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure d\'affectation',
                'data' => new \DateTime() // Valeur par défaut
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectationinterview::class,
            'interview' => null,
        ]);
    }
}

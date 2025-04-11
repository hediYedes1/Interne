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
        
            ->add('idutilisateur', EntityType::class, [
                'class' => Utilisateur::class,
'choice_label' => 'idutilisateur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brancheentreprise::class,
        ]);
    }
}

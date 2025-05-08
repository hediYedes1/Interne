<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Entity\Partenariat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomhebergement')
            ->add('adressehebergement')
            ->add('typehebergement')
            ->add('descriptionhebergement')
            ->add('nbrnuitehebergement')
            ->add('disponibilitehebergement')
            ->add('localisationhebergement')
            ->add('prixhebergement')
            ->add('idpartenariat', EntityType::class, [
                'class' => Partenariat::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
            'attr' => [
                'novalidate' => 'novalidate' // Disable HTML5 validation
            ]
        ]);
    }
}

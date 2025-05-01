<?php

namespace App\Form;

use App\Entity\Brancheentreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class BrancheentrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombranche', TextType::class, [
                'label' => 'Nom de la branche',
                'attr' => [
                    'placeholder' => 'Entrez le nom de la branche'
                ]
            ])
            ->add('adressebranche', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse de la branche'
                ]
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'scale' => 6,
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'scale' => 6,
                'attr' => [
                    'readonly' => true
                ]
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
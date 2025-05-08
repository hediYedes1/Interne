<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Entity\Partenariat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Hebergement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomhebergement', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('adressehebergement', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('typehebergement', ChoiceType::class, [
                'choices' => [
                    'Hôtel' => 'Hôtel',
                    'Appartement' => 'Appartement',
                    'Auberge' => 'Auberge'
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('descriptionhebergement', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('nbrnuitehebergement', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'min' => 1]
            ])
            ->add('disponibilitehebergement', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('localisationhebergement', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prixhebergement', NumberType::class, [
                'attr' => ['class' => 'form-control', 'step' => '0.01'],
                'html5' => true
            ])
            ->add('idpartenariat', EntityType::class, [
                'class' => Partenariat::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-select']
            ]);
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
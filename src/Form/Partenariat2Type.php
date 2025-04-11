<?php

namespace App\Form;

use App\Entity\Brancheentreprise;
use App\Entity\Partenariat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Partenariat2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nompartenariat')
            ->add('adressepartenariat')
            ->add('numtelpartenariat')
            ->add('idbranche', EntityType::class, [
                'class' => Brancheentreprise::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partenariat::class,
            'attr' => [
                'novalidate' => 'novalidate' // Disable HTML5 validation
            ]
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Interview;
use App\Entity\Testtechnique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;



class TesttechniqueType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('titretesttechnique')
        ->add('descriptiontesttechnique')
        ->add('dureetesttechnique');
        

    // Only add idinterview field if no interview is passed in options
    if (empty($options['interview'])) {
        $builder->add('idinterview', EntityType::class, [
            'class' => Interview::class,
            'choice_label' => 'titreoffre',
            'label' => 'Interview associée',
            'attr' => ['class' => 'form-select form-select-lg']
        ]);
    }
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Testtechnique::class,
        'interview' => null,
    ]);
    
    $resolver->setAllowedTypes('interview', ['null', Interview::class]);
}
}
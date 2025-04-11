<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idprojet')
            ->add('titreprojet')
            ->add('descriptionprojet')
            ->add('datedebut', null, [
                'widget' => 'single_text',
            ])
            ->add('datefin', null, [
                'widget' => 'single_text',
            ])
            ->add('idoffre', EntityType::class, [
                'class' => Offre::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}

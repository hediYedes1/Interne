<?php

namespace App\Form;

use App\Entity\Brancheentreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class BrancheentrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('localisationbranche', TextType::class, [
                'label' => 'Localisation',
                'attr' => [
                    'placeholder' => 'Entrez la localisation de la branche'
                ]
            ])
            ->add('emailbranche', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'exemple@esprit.tn'
                ]
            ])
            ->add('contactbranche', TelType::class, [
                'label' => 'Contact',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de contact'
                ]
            ])
            ->add('nombreemploye', IntegerType::class, [
                'label' => 'Nombre d\'employés',
                'attr' => [
                    'placeholder' => 'Entrez le nombre d\'employés'
                ]
            ])
            ->add('responsablebranche', TextType::class, [
                'label' => 'Responsable',
                'attr' => [
                    'placeholder' => 'Entrez le nom du responsable'
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
<?php

namespace App\Form;

use App\Entity\Departmententreprise;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmententrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomdepartement', null, [
                'label' => 'Nom du Département',
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du département',
                ],
            ])
            ->add('descriptiondepartement', null, [
                'label' => 'Description',
                'attr' => [
                    'maxlength' => 500,
                    'class' => 'form-control',
                    'placeholder' => 'Entrez une description',
                ],
            ])
            ->add('responsabledepartement', null, [
                'label' => 'Responsable',
                'attr' => [
                    'maxlength' => 100,
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du responsable',
                ],
            ])
            ->add('nbremployedepartement', null, [
                'label' => 'Nombre d\'Employés',
                'attr' => [
                    'min' => 1,
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nombre d\'employés',
                ],
            ])
            ->add('identreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomentreprise', // Remplacez par le champ à afficher dans le menu déroulant
                'label' => 'Entreprise',
                'placeholder' => 'Sélectionnez une entreprise',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Departmententreprise::class,
        ]);
    }
}
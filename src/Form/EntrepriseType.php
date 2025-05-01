<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import FileType
use Symfony\Component\Validator\Constraints as Assert;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomentreprise', null, [
                'label' => 'Nom de l\'entreprise',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('descriptionentreprise', null, [
                'label' => 'Description de l\'entreprise',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Décrivez l\'entreprise'],
            ])
            ->add('logoentreprise', FileType::class, [
                'label' => 'Logo de l\'entreprise (facultatif)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*' // Limite aux fichiers images
                ],
                'mapped' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, WebP ou SVG)',
                    ]),
                ],
            ])
            ->add('urlentreprise', null, [
                'label' => 'URL de l\'entreprise',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('secteurentreprise', null, [
                'label' => 'Secteur d\'activité',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
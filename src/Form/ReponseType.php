<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Reponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('contenuReponse', TextareaType::class, [
            'constraints' => [
                new NotBlank(['message' => 'La réponse ne peut pas être vide']),
                new Length([
                    'min' => 2,
                    'minMessage' => 'La réponse doit contenir au moins {{ limit }} caractères',
                ]),
            ],
            'attr' => ['rows' => 2, 'placeholder' => 'Votre réponse...'],
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Reponse::class,
    ]);
}
}
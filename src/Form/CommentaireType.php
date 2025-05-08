<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Publication;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu')
            ->add('dateCommentaire', null, [
                'widget' => 'single_text',
            ])
            ->add('likes')
            ->add('dislikes')
            ->add('idPublication', EntityType::class, [
                'class' => Publication::class,
                'choice_label' => 'idPublication',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}

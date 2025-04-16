<?php

namespace App\Form;

use App\Entity\Affectationinterview;
use App\Entity\Interview;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;

class AffectationinterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idutilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function(Utilisateur $utilisateur) {
                    return $utilisateur->getNomutilisateur() . ' ' . $utilisateur->getPrenomutilisateur();
                },
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin('u.affectationinterviews', 'a')
                        ->andWhere('a.idutilisateur IS NULL OR a.idinterview != :interview')
                        ->setParameter('interview', $options['interview']);
                },
                'label' => 'Utilisateur',
                'placeholder' => 'Sélectionnez un utilisateur',
                'attr' => ['class' => 'select2'] // Pour améliorer l'UX avec Select2
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectationinterview::class,
            'interview' => null,
        ]);
    }
}

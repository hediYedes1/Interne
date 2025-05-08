<?php

namespace App\Form;

use App\Entity\Affectationinterview;
use App\Entity\Interview;
use App\Entity\Utilisateur;
use App\Enum\Role;
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
                    $qb = $er->createQueryBuilder('u')
                        ->andWhere('u.role = :role')
                        ->setParameter('role', Role::CANDIDAT->value);

                    if ($options['interview']) {
                        $qb->leftJoin('u.affectationinterviews', 'a', 'WITH', 'a.idinterview = :interview')
                            ->andWhere('a.idutilisateur IS NULL')
                            ->setParameter('interview', $options['interview']);
                    }

                    return $qb;
                },
                'label' => 'Candidat',
                'placeholder' => 'Sélectionnez un candidat',
                'attr' => ['class' => 'select2']
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
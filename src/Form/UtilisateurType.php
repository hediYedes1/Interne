<?php
namespace App\Form;

use App\Entity\Utilisateur;
use App\Enum\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomutilisateur', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenomutilisateur', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('ageutilisateur', IntegerType::class, [
                'label' => 'Âge',
            ])
            ->add('emailutilisateur', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('motdepasseutilisateur', PasswordType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Candidat' => Role::CANDIDAT,
                    'Ressources Humaines' => Role::RH,
                    'Administrateur' => Role::ADMIN,
                    'Manager' => Role::MANAGER,
                ],
                'label' => 'Rôle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
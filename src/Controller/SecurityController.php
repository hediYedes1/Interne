<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\Role;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getUser();
        if ($user) {
            $roles = $user->getRoles();
            if (in_array(Role::CANDIDAT->value, $roles, true)) {
                return $this->redirectToRoute('app_base');

            } elseif (in_array(Role::RH->value, $roles, true)) {
                return $this->redirectToRoute('app_base2');
            } else {
                return $this->redirectToRoute('app_base2');
            }
            
        }

        return $this->render('utilisateur/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $user->setNomutilisateur($request->request->get('nom'));
            $user->setPrenomutilisateur($request->request->get('prenom'));
            $user->setAgeutilisateur((int) $request->request->get('age'));
            $user->setEmailutilisateur($request->request->get('email'));
            $user->setMotdepasseutilisateur($passwordHasher->hashPassword($user, $request->request->get('password')));
            $user->setRole(Role::CANDIDAT); // Assuming 'CANDIDAT' is a valid role in your system
            $user->setResettoken('');
            $user->setProfilepictureurl('default.jpg');

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_base3');
        }

        return $this->render('utilisateur/signUp.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony handles the logout automatically
    }
}

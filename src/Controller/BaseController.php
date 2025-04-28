<?php

namespace App\Controller;

use App\Enum\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class BaseController extends AbstractController
{
    #[Route('/base1', name: 'app_base')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $roles = $user->getRoles();
            if (in_array(Role::CANDIDAT, $roles, true)) {
                return $this->render('base.html.twig', [
                    'user' => $user,
                ]);
            } elseif (in_array(Role::ADMIN, $roles, true)) {
                return $this->render('base1.html.twig', [
                    'user' => $user,
                ]);
            }
        }

        return $this->render('base.html.twig');
    }


    #[Route('/base2', name: 'app_base2')]
    public function index2(): Response
    {
        return $this->render('base1.html.twig');
    }

    #[Route('/base3', name: 'app_base3')]
    public function index3(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Utilisateur/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/base4', name: 'app_base4')]
    public function index4(): Response
    {
        return $this->render('Utilisateur/signUp.html.twig');
    }

    #[Route('/base5', name: 'app_base5')]
    public function index5(): Response
    {
        return $this->render('Utilisateur/profile.html.twig');
    }

    #[Route('/base6', name: 'app_base6')]
    public function index6(): Response
    {
        return $this->render('Utilisateur/profile.html.twig');
    }

    #[Route('/base7', name: 'app_userProfile')]
    public function index7(): Response
    {
        return $this->render('Utilisateur/utilisateur.html.twig');
    }

    #[Route('/base8', name: 'app_adminProfile')]
    public function index8(): Response
    {
        return $this->render('Utilisateur/adminProfile.html.twig');
    }
    

}

<?php

namespace App\Controller;

use App\Enum\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\CaptchaService;

final class BaseController extends AbstractController
{
    private CaptchaService $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    // Candidat interface
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

    // RH interface
    #[Route('/baseRH', name: 'app_base2')]
    public function indexRH(): Response
    {
        return $this->render('base2.html.twig');
    }

    // Admin interface
    #[Route('/base2', name: 'app_base1')]
    public function index2(): Response
    {
        return $this->render('base1.html.twig');
    }

    #[Route('/base3', name: 'app_base3')]
    public function index3(AuthenticationUtils $authenticationUtils): Response
    {
        // If user is already logged in, redirect to appropriate page
        if ($this->getUser()) {
            $roles = $this->getUser()->getRoles();
            if (in_array(Role::CANDIDAT->value, $roles, true)) {
                return $this->redirectToRoute('app_base');
            } elseif (in_array(Role::RH->value, $roles, true)) {
                return $this->redirectToRoute('app_base2');
            } elseif (in_array(Role::ADMIN->value, $roles, true)) {
                return $this->redirectToRoute('app_base1');
            }
        }

        // Generate CAPTCHA
        $captcha = $this->captchaService->generateCaptcha();
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('utilisateur/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'captcha' => $captcha
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

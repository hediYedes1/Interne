<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PasswordResetController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['emailutilisateur' => $email]);

            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $user->setResettoken($token);
                $this->entityManager->flush();

                // Send reset email
                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], true);
                $email = (new Email())
                    ->from('noreply@yourdomain.com')
                    ->to($user->getEmailutilisateur())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html($this->renderView('emails/reset_password.html.twig', [
                        'resetUrl' => $resetUrl,
                        'user' => $user
                    ]));

                try {
                    $this->mailer->send($email);
                    $this->addFlash('success', 'Un email de réinitialisation a été envoyé à votre adresse email.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer plus tard.');
                }
            } else {
                // Don't reveal if the email exists or not
                $this->addFlash('success', 'Si votre adresse email existe dans notre base de données, vous recevrez un email de réinitialisation.');
            }

            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->render('utilisateur/forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token): Response
    {
        $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['resettoken' => $token]);

        if (!$user) {
            throw new AccessDeniedException('Token de réinitialisation invalide.');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            // Update password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setMotdepasseutilisateur($hashedPassword);
            $user->setResettoken(null);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('utilisateur/reset_password.html.twig', [
            'token' => $token
        ]);
    }
} 
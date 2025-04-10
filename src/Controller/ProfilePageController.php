<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ProfileImageType;
use App\Form\ProfileUpdateType;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilePageController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile.html.twig');
    }

    #[Route('/update-profile', name: 'update_profile', methods: ['POST'])]
    public function updateProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return $this->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ]);
        }

        $user->setNomutilisateur($request->request->get('nomutilisateur'));
        $user->setPrenomutilisateur($request->request->get('prenomutilisateur'));
        $user->setEmailutilisateur($request->request->get('emailutilisateur'));
        $user->setAgeutilisateur((int)$request->request->get('ageutilisateur'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès'
        ]);
    }

    #[Route('/update-profile-image', name: 'update_profile_image', methods: ['POST'])]
    public function updateProfileImage(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return $this->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ]);
        }

        $file = $request->files->get('profile_image');
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('profile_images_directory'),
                    $newFilename
                );
                
                if ($user->getProfilepictureurl()) {
                    $oldImagePath = $this->getParameter('profile_images_directory').'/'.$user->getProfilepictureurl();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $user->setProfilepictureurl($newFilename);
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->json([
                    'success' => true,
                    'message' => 'Photo de profil mise à jour avec succès',
                    'imageUrl' => $this->generateUrl('app_profile') // Return the profile page URL
                ]);
            } catch (FileException $e) {
                return $this->json([
                    'success' => false,
                    'message' => 'Erreur lors du téléchargement de l\'image'
                ]);
            }
        }

        return $this->json([
            'success' => false,
            'message' => 'Aucun fichier téléchargé'
        ]);
    }

    #[Route('/update-password', name: 'update_password', methods: ['POST'])]
    public function updatePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return $this->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ]);
        }

        $currentPassword = $request->request->get('current_password');
        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');

        // Verify current password
        if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
            return $this->json([
                'success' => false,
                'message' => 'Mot de passe actuel incorrect'
            ]);
        }

        // Verify new password matches confirmation
        if ($newPassword !== $confirmPassword) {
            return $this->json([
                'success' => false,
                'message' => 'Les nouveaux mots de passe ne correspondent pas'
            ]);
        }

        // Validate password strength
        if (strlen($newPassword) < 8 || 
            !preg_match('/[A-Z]/', $newPassword) || 
            !preg_match('/[a-z]/', $newPassword) || 
            !preg_match('/[0-9]/', $newPassword)) {
            return $this->json([
                'success' => false,
                'message' => 'Le mot de passe doit contenir au moins 8 caractères avec une majuscule, une minuscule et un chiffre'
            ]);
        }

        // Hash and set new password
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setMotdepasseutilisateur($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }
}
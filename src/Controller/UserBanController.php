<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user-ban')]
class UserBanController extends AbstractController
{
    #[Route('/', name: 'app_user_ban_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get all users except admins
        $users = $entityManager->getRepository(Utilisateur::class)
            ->createQueryBuilder('u')
            ->where('u.role != :adminRole')
            ->setParameter('adminRole', Role::ADMIN)
            ->getQuery()
            ->getResult();

        return $this->render('user_ban/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}/ban', name: 'app_user_ban', methods: ['POST'])]
    public function ban(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getRole() === Role::ADMIN) {
            $this->addFlash('error', 'Cannot ban an admin user');
            return $this->redirectToRoute('app_user_ban_index');
        }

        $user->setIsBanned(true);
        $entityManager->flush();

        $this->addFlash('success', 'User has been banned successfully');
        return $this->redirectToRoute('app_user_ban_index');
    }

    #[Route('/{id}/unban', name: 'app_user_unban', methods: ['POST'])]
    public function unban(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getRole() === Role::ADMIN) {
            $this->addFlash('error', 'Cannot unban an admin user');
            return $this->redirectToRoute('app_user_ban_index');
        }

        $user->setIsBanned(false);
        $entityManager->flush();

        $this->addFlash('success', 'User has been unbanned successfully');
        return $this->redirectToRoute('app_user_ban_index');
    }
} 
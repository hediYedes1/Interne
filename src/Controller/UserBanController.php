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
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search', '');
        $status = $request->query->get('status', 'all');

        $qb = $entityManager->getRepository(Utilisateur::class)
            ->createQueryBuilder('u')
            ->where('u.role != :adminRole')
            ->setParameter('adminRole', Role::ADMIN);

        if ($search) {
            $qb->andWhere('u.nomutilisateur LIKE :search OR u.prenomutilisateur LIKE :search OR u.emailutilisateur LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($status !== 'all') {
            $qb->andWhere('u.isBanned = :status')
                ->setParameter('status', $status === 'banned');
        }

        $users = $qb->getQuery()->getResult();

        return $this->render('user_ban/index.html.twig', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
        ]);
    }

    #[Route('/{id}/ban', name: 'app_user_ban', methods: ['POST'])]
    public function ban(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getRole() === Role::ADMIN) {
            $this->addFlash('error', 'Impossible de bannir un administrateur');
            return $this->redirectToRoute('app_user_ban_index');
        }

        $user->setIsBanned(true);
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été banni avec succès');
        return $this->redirectToRoute('app_user_ban_index');
    }

    #[Route('/{id}/unban', name: 'app_user_unban', methods: ['POST'])]
    public function unban(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getRole() === Role::ADMIN) {
            $this->addFlash('error', 'Impossible de débannir un administrateur');
            return $this->redirectToRoute('app_user_ban_index');
        }

        $user->setIsBanned(false);
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été débanni avec succès');
        return $this->redirectToRoute('app_user_ban_index');
    }
} 
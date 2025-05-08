<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{
    #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search', ''); 
        $field = $request->query->get('field', 'nomutilisateur'); 
        $sort = $request->query->get('sort', 'nomutilisateur'); 
        $direction = $request->query->get('direction', 'asc'); 

        $allowedFields = ['nomutilisateur', 'prenomutilisateur', 'emailutilisateur', 'ageutilisateur'];
        if (!in_array($field, $allowedFields)) {
            $field = 'nomutilisateur';
        }

        $queryBuilder = $entityManager->getRepository(Utilisateur::class)->createQueryBuilder('u');

        if (!empty($search)) {
            $queryBuilder->where("u.$field LIKE :search")
                ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('u.' . $sort, $direction);

        $utilisateurs = $queryBuilder->getQuery()->getResult();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'search' => $search,
            'field' => $field,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{idutilisateur}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{idutilisateur}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idutilisateur}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getIdutilisateur(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
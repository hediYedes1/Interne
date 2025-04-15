<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/projet')]
final class ProjetController extends AbstractController
{
    // Route pour afficher la liste des projets
    #[Route('/list', name: 'app_projet_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projets = $entityManager
            ->getRepository(Projet::class)
            ->findAll();

        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }

    // Route pour créer un nouveau projet
    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté (si besoin) et associer au projet
            $user = $security->getUser();
            if ($user) {
                $projet->setIdutilisateur($user);
            }

            // Sauvegarder le projet dans la base de données
            $entityManager->persist($projet);
            $entityManager->flush();

            // Rediriger vers la liste des projets après création
            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),  // Utiliser createView() pour rendre le formulaire
        ]);
    }

    // Route pour afficher un projet spécifique
    #[Route('/{idprojet}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    // Route pour modifier un projet existant
    #[Route('/{idprojet}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications du projet dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des projets après édition
            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),  // Utiliser createView() pour rendre le formulaire
        ]);
    }

    // Route pour supprimer un projet
    #[Route('/{idprojet}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF avant de supprimer le projet
        if ($this->isCsrfTokenValid('delete'.$projet->getIdprojet(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        // Rediriger vers la liste des projets après suppression
        return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
    }
}

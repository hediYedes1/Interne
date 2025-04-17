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
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Route pour la partie front (candidats)
    #[Route('/list', name: 'front_projet_index', methods: ['GET'])]
    public function indexFront(Request $request): Response
    {
        $projets = $this->entityManager
            ->getRepository(Projet::class)
            ->findAll();

        return $this->render('projet/indexfront.html.twig', [
            'projets' => $projets,
        ]);
    }

    // Route pour la partie back (admin/RH)
    #[Route('/admin/projets', name: 'back_projet_index', methods: ['GET'])]
    public function indexBack(Request $request): Response
    {
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();
        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }

    // Route pour créer un nouveau projet (back)
    #[Route('/admin/new', name: 'back_projet_new', methods: ['GET', 'POST'])]
    public function newBack(Request $request, Security $security): Response
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
            $this->entityManager->persist($projet);
            $this->entityManager->flush();

            // Rediriger vers la liste des projets après création
            return $this->redirectToRoute('back_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),  // Utiliser createView() pour rendre le formulaire
        ]);
    }

    // Route pour afficher un projet spécifique (front)
    #[Route('/{idprojet}', name: 'front_projet_show', methods: ['GET'])]
    public function showFront(Projet $projet): Response
    {
        return $this->render('projet/showfront.html.twig', [
            'projet' => $projet,
            'offres' => $projet->getOffres()
        ]);
    }

    // Route pour afficher un projet spécifique (back)
    #[Route('/admin/{idprojet}', name: 'back_projet_show', methods: ['GET'])]
    public function showBack(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            'offres' => $projet->getOffres()
        ]);
    }

    // Route pour modifier un projet existant (back)
    #[Route('/admin/{idprojet}/edit', name: 'back_projet_edit', methods: ['GET', 'POST'])]
    public function editBack(Request $request, Projet $projet): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications du projet dans la base de données
            $this->entityManager->flush();

            // Rediriger vers la liste des projets après édition
            return $this->redirectToRoute('back_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),  // Utiliser createView() pour rendre le formulaire
        ]);
    }

    // Route pour supprimer un projet (back)
    #[Route('/admin/{idprojet}', name: 'back_projet_delete', methods: ['POST'])]
    public function deleteBack(Request $request, Projet $projet): Response
    {
        // Vérification du token CSRF avant de supprimer le projet
        if ($this->isCsrfTokenValid('delete'.$projet->getIdprojet(), $request->request->get('_token'))) {
            $this->entityManager->remove($projet);
            $this->entityManager->flush();
        }

        // Rediriger vers la liste des projets après suppression
        return $this->redirectToRoute('back_projet_index', [], Response::HTTP_SEE_OTHER);
    }
}

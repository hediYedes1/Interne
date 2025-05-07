<?php
// src/Controller/ProjetController.php
namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
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
    private ProjetRepository $projetRepository;

    public function __construct(EntityManagerInterface $entityManager, ProjetRepository $projetRepository)
    {
        $this->entityManager = $entityManager;
        $this->projetRepository = $projetRepository;
    }

    #[Route('/front/list', name: 'front_projet_index', methods: ['GET'])]
    public function indexFront(Request $request): Response
    {
        $searchQuery = $request->query->get('search', '');
        $projets = $this->projetRepository->search($searchQuery);

        if ($request->isXmlHttpRequest()) {
            return $this->render('projet/_projets_list.html.twig', [
                'projets' => $projets
            ]);
        }

        return $this->render('projet/indexfront.html.twig', [
            'projets' => $projets,
            'searchQuery' => $searchQuery
        ]);
    }
    #[Route('/rh/list', name: 'rh_projet_index', methods: ['GET'])]
    public function indexrh(Request $request): Response
    {
        $searchQuery = $request->query->get('search', '');
        $projets = $this->projetRepository->search($searchQuery);

        if ($request->isXmlHttpRequest()) {
            return $this->render('projet/_projets_list.html.twig', [
                'projets' => $projets
            ]);
        }

        return $this->render('projet/indexrh.html.twig', [
            'projets' => $projets,
            'searchQuery' => $searchQuery
        ]);
    }

    #[Route('/rh/new', name: 'rh_projet_new', methods: ['GET', 'POST'])]
    public function newrh(Request $request, Security $security): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            if ($user) {
                $projet->setIdutilisateur($user);
            }

            $this->entityManager->persist($projet);
            $this->entityManager->flush();

            $this->addFlash('success', 'Projet créé avec succès');
            return $this->redirectToRoute('rh_projet_index');
        }

        return $this->render('projet/newrh.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rh/{idprojet}/edit', name: 'rh_projet_edit', methods: ['GET', 'POST'])]
    public function editFront(Request $request, Projet $projet): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Projet modifié avec succès');
            return $this->redirectToRoute('rh_projet_index');
        }

        return $this->render('projet/editrh.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/front/{idprojet}', name: 'front_projet_show', methods: ['GET'])]
    public function showFront(Projet $projet): Response
    {
        return $this->render('projet/showfront.html.twig', [
            'projet' => $projet,
            'offres' => $projet->getOffres()
        ]);
    }
    #[Route('/rh/{idprojet}', name: 'rh_projet_show', methods: ['GET'])]
    public function showrh(Projet $projet): Response
    {
        return $this->render('projet/showrh.html.twig', [
            'projet' => $projet,
            'offres' => $projet->getOffres()
        ]);
    }

    #[Route('/rh/{idprojet}', name: 'rh_projet_delete', methods: ['POST'])]
    public function deleterh(Request $request, Projet $projet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getIdprojet(), $request->request->get('_token'))) {
            $this->entityManager->remove($projet);
            $this->entityManager->flush();
            $this->addFlash('success', 'Projet supprimé avec succès');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression du projet');
        }

        return $this->redirectToRoute('rh_projet_index');
    }

    #[Route('/admin/projets', name: 'back_projet_index', methods: ['GET'])]
    public function indexBack(Request $request): Response
    {
        $searchQuery = $request->query->get('search', '');
        $projets = $this->projetRepository->search($searchQuery);

        if ($request->isXmlHttpRequest()) {
            return $this->render('projet/_projets_list.html.twig', [
                'projets' => $projets
            ]);
        }

        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
            'searchQuery' => $searchQuery
        ]);
    }

    #[Route('/admin/new', name: 'back_projet_new', methods: ['GET', 'POST'])]
    public function newBack(Request $request, Security $security): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            if ($user) {
                $projet->setIdutilisateur($user);
            }

            $this->entityManager->persist($projet);
            $this->entityManager->flush();

            $this->addFlash('success', 'Projet créé avec succès');
            return $this->redirectToRoute('back_projet_index');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{idprojet}/edit', name: 'back_projet_edit', methods: ['GET', 'POST'])]
    public function editBack(Request $request, Projet $projet): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Projet modifié avec succès');
            return $this->redirectToRoute('back_projet_index');
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{idprojet}', name: 'back_projet_show', methods: ['GET'])]
    public function showBack(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            'offres' => $projet->getOffres()
        ]);
    }

    #[Route('/admin/{idprojet}', name: 'back_projet_delete', methods: ['POST'])]
    public function deleteBack(Request $request, Projet $projet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getIdprojet(), $request->request->get('_token'))) {
            $this->entityManager->remove($projet);
            $this->entityManager->flush();
            $this->addFlash('success', 'Projet supprimé avec succès');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression du projet');
        }

        return $this->redirectToRoute('back_projet_index');
    }
}
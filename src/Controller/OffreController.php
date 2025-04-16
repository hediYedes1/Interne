<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Projet;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/offre')]
class OffreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/list', name: 'app_offre_index', methods: ['GET'])]
    public function index(): Response
    {
        $offres = $this->entityManager
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Security $security): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            if ($user) {
                $offre->setIdutilisateur($user);
                
                // Si vous avez une relation entre User et Entreprise
                if (method_exists($user, 'getEntreprise')) {
                    $offre->setIdentreprise($user->getEntreprise());
                }
            }

            $this->entityManager->persist($offre);
            $this->entityManager->flush();

            $this->addFlash('success', 'Offre créée avec succès');
            return $this->redirectToRoute('app_offre_index');
        }

        return $this->render('offre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idoffre}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'projet' => $offre->getProjet()
        ]);
    }

    #[Route('/{idoffre}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre modifiée avec succès');
            return $this->redirectToRoute('app_offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idoffre}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès');
        }

        return $this->redirectToRoute('app_offre_index');
    }
}
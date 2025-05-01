<?php

namespace App\Controller;

use App\Entity\Brancheentreprise;
use App\Entity\Entreprise;
use App\Form\BrancheentrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brancheentreprise')]
final class BrancheentrepriseController extends AbstractController
{
    #[Route('/list', name: 'app_brancheentreprise_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $brancheentreprises = $entityManager
            ->getRepository(Brancheentreprise::class)
            ->findAll();

        return $this->render('brancheentreprise/index.html.twig', [
            'brancheentreprises' => $brancheentreprises,
        ]);
    }

    #[Route('/new/{id}', name: 'app_brancheentreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $brancheentreprise = new Brancheentreprise();
        $form = $this->createForm(BrancheentrepriseType::class, $brancheentreprise);
        $form->handleRequest($request);

        // Récupération de l'entreprise
        $entreprise = $entityManager->getRepository(Entreprise::class)->find($id);

        if (!$entreprise) {
            throw $this->createNotFoundException('Entreprise non trouvée avec l\'ID : ' . $id);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $brancheentreprise->setIdentreprise($entreprise);
            
            // Récupérer les coordonnées du champ caché
            $coords = $request->request->get('location_coords');
            if ($coords) {
                $brancheentreprise->setLocalisationbranche($coords);
            }
            
            $entityManager->persist($brancheentreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_show_back', [
                'identreprise' => $entreprise->getIdentreprise(),
            ]);
        }

        return $this->render('brancheentreprise/new.html.twig', [
            'brancheentreprise' => $brancheentreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{idbranche}', name: 'app_brancheentreprise_show', methods: ['GET'])]
    public function show(Brancheentreprise $brancheentreprise): Response
    {
        return $this->render('brancheentreprise/show.html.twig', [
            'brancheentreprise' => $brancheentreprise,
        ]);
    }

    #[Route('/{idbranche}/edit', name: 'app_brancheentreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brancheentreprise $brancheentreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BrancheentrepriseType::class, $brancheentreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les coordonnées du champ caché
            $coords = $request->request->get('location_coords');
            if ($coords) {
                $brancheentreprise->setLocalisationbranche($coords);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_show_back', [
                'identreprise' => $brancheentreprise->getIdentreprise()->getIdentreprise(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('brancheentreprise/edit.html.twig', [
            'brancheentreprise' => $brancheentreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{idbranche}', name: 'app_brancheentreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Brancheentreprise $brancheentreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $brancheentreprise->getIdbranche(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($brancheentreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entreprise_show_back', [
            'identreprise' => $brancheentreprise->getIdentreprise()->getIdentreprise(),
        ], Response::HTTP_SEE_OTHER);
    }}
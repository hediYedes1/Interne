<?php

namespace App\Controller;

use App\Entity\Affectationhebergement;
use App\Form\AffectationhebergementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/affectationhebergement')]
final class AffectationhebergementController extends AbstractController
{
    #[Route(name: 'app_affectationhebergement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $affectationhebergements = $entityManager
            ->getRepository(Affectationhebergement::class)
            ->findAll();

        return $this->render('affectationhebergement/index.html.twig', [
            'affectationhebergements' => $affectationhebergements,
        ]);
    }

    #[Route('/new', name: 'app_affectationhebergement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $affectationhebergement = new Affectationhebergement();
        $form = $this->createForm(AffectationhebergementType::class, $affectationhebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($affectationhebergement);
            $entityManager->flush();

            return $this->redirectToRoute('app_affectationhebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectationhebergement/new.html.twig', [
            'affectationhebergement' => $affectationhebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_affectationhebergement_show', methods: ['GET'])]
    public function show(Affectationhebergement $affectationhebergement): Response
    {
        return $this->render('affectationhebergement/show.html.twig', [
            'affectationhebergement' => $affectationhebergement,
        ]);
    }

    #[Route('/{idhebergement}/edit', name: 'app_affectationhebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Affectationhebergement $affectationhebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AffectationhebergementType::class, $affectationhebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_affectationhebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectationhebergement/edit.html.twig', [
            'affectationhebergement' => $affectationhebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_affectationhebergement_delete', methods: ['POST'])]
    public function delete(Request $request, Affectationhebergement $affectationhebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectationhebergement->getIdhebergement(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($affectationhebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_affectationhebergement_index', [], Response::HTTP_SEE_OTHER);
    }
}

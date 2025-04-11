<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Form\Hebergement1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hebergement/admin')]
final class HebergementAdminController extends AbstractController
{
    #[Route(name: 'app_hebergement_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $hebergements = $entityManager
            ->getRepository(Hebergement::class)
            ->findAll();

        return $this->render('hebergement_admin/index.html.twig', [
            'hebergements' => $hebergements,
        ]);
    }

    #[Route('/new', name: 'app_hebergement_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(Hebergement1Type::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hebergement);
            $entityManager->flush();

            return $this->redirectToRoute('app_hebergement_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hebergement_admin/new.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_hebergement_admin_show', methods: ['GET'])]
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('hebergement_admin/show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    #[Route('/{idhebergement}/edit', name: 'app_hebergement_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Hebergement1Type::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_hebergement_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hebergement_admin/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_hebergement_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hebergement->getIdhebergement(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($hebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hebergement_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}

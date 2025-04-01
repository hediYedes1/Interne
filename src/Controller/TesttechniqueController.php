<?php

namespace App\Controller;

use App\Entity\Testtechnique;
use App\Entity\Interview;
use App\Form\TesttechniqueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/testtechnique')]
final class TesttechniqueController extends AbstractController
{
    #[Route(name: 'app_testtechnique_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $testtechniques = $entityManager
            ->getRepository(Testtechnique::class)
            ->findAll();

        return $this->render('testtechnique/index.html.twig', [
            'testtechniques' => $testtechniques,
        ]);
    }

    #[Route('/new', name: 'app_testtechnique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testtechnique = new Testtechnique();
        $form = $this->createForm(TesttechniqueType::class, $testtechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($testtechnique);
            $entityManager->flush();

            return $this->redirectToRoute('app_testtechnique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testtechnique/new.html.twig', [
            'testtechnique' => $testtechnique,
            'form' => $form,
        ]);
    }

    #[Route('/{idtesttechnique}', name: 'app_testtechnique_show', methods: ['GET'])]
    public function show(Testtechnique $testtechnique): Response
    {
        return $this->render('testtechnique/show.html.twig', [
            'testtechnique' => $testtechnique,
        ]);
    }

    #[Route('/{idtesttechnique}/edit', name: 'app_testtechnique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testtechnique $testtechnique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TesttechniqueType::class, $testtechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_testtechnique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testtechnique/edit.html.twig', [
            'testtechnique' => $testtechnique,
            'form' => $form,
        ]);
    }

    #[Route('/{idtesttechnique}', name: 'app_testtechnique_delete', methods: ['POST'])]
    public function delete(Request $request, Testtechnique $testtechnique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$testtechnique->getIdtesttechnique(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($testtechnique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_testtechnique_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/interview/{id}/tests', name: 'app_testtechnique_by_interview', methods: ['GET'])]
public function indexByInterview(Interview $interview): Response
{
    $tests = $interview->getTestTechniques(); // Récupérer les tests techniques liés à l'interview

    return $this->render('testtechnique/index.html.twig', [
        'testtechniques' => $tests,
        'interview' => $interview,
    ]);
}

   
}

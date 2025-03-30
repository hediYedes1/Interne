<?php

namespace App\Controller;

use App\Entity\Interview;
use App\Form\InterviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/interview')]
final class InterviewController extends AbstractController
{
    #[Route('/list', name: 'app_interview_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $interviews = $entityManager
            ->getRepository(Interview::class)
            ->findAll();

        return $this->render('interview/index.html.twig', [
            'interviews' => $interviews,
        ]);
    }

    #[Route('/new', name: 'app_interview_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $interview = new Interview();
        $form = $this->createForm(InterviewType::class, $interview);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Copiez le titre de l'offre vers titreoffre
            $interview->setTitreoffre($interview->getIdoffre()->getTitreoffre());
            
            $entityManager->persist($interview);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_interview_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('interview/new.html.twig', [
            'interview' => $interview,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idinterview}', name: 'app_interview_show', methods: ['GET'])]
    public function show(Interview $interview): Response
    {
        return $this->render('interview/show.html.twig', [
            'interview' => $interview,
        ]);
    }

    #[Route('/{idinterview}/edit', name: 'app_interview_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Interview $interview, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterviewType::class, $interview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_interview_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interview/edit.html.twig', [
            'interview' => $interview,
            'form' => $form,
        ]);
    }

    #[Route('/{idinterview}/delete', name: 'app_interview_delete', methods: ['POST'])]
    public function delete(Request $request, Interview $interview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interview->getIdinterview(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($interview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_interview_index', [], Response::HTTP_SEE_OTHER);
    }
}

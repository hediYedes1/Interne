<?php

namespace App\Controller;

use App\Entity\Interview;
use App\Form\InterviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Testtechnique;
use App\Repository\InterviewRepository;
use App\Enum\TypeInterview;

#[Route('/interview')]
final class InterviewController extends AbstractController
{
// src/Controller/InterviewController.php

#[Route('/list', name: 'app_interview_index', methods: ['GET'])]
public function index(Request $request, InterviewRepository $interviewRepository): Response
{
    $titre = $request->query->get('titreoffre');
    $type = $request->query->get('typeinterview');
    
    $interviews = $interviewRepository->findByFilters($titre, $type);
    
    return $this->render('interview/index.html.twig', [
        'interviews' => $interviews,
    ]);
}
#[Route('/listBack', name: 'app_interview_index_back', methods: ['GET'])]
public function indexBack(Request $request, InterviewRepository $interviewRepository): Response
{
    $titre = $request->query->get('titreoffre');
    $type = $request->query->get('typeinterview');
    
    $interviews = $interviewRepository->findByFilters($titre, $type);
    
    return $this->render('interview/indexBack.html.twig', [
        'interviews' => $interviews,
    ]);
}

#[Route('/listFront', name: 'app_interview_index_front', methods: ['GET'])]
public function indexFront(Request $request, InterviewRepository $interviewRepository): Response
{
    $titre = $request->query->get('titreoffre');
    $type = $request->query->get('typeinterview');
    
    $interviews = $interviewRepository->findByFilters($titre, $type);
    
    return $this->render('interview/indexFront.html.twig', [
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

    #[Route('/{idinterview}/back', name: 'app_interview_show_back', methods: ['GET'])]
    public function showBack(Interview $interview): Response
    {
        return $this->render('interview/showBack.html.twig', [
            'interview' => $interview,
        ]);
    }

    #[Route('/{idinterview}/front', name: 'app_interview_show_front', methods: ['GET'])]
    public function showFront(Interview $interview): Response
    {
        return $this->render('interview/showFront.html.twig', [
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
    #[Route('/{idinterview}/tests', name: 'app_interview_tests', methods: ['GET'])]
    public function getTestsByInterview(Interview $interview): Response
    {
        $tests = $interview->getTesttechniques();
        
        return $this->render('interview/tests.html.twig', [
            'interview' => $interview,
            'tests' => $tests,
        ]);
    }

}
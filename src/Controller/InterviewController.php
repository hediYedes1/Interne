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
use App\Utils\GoogleMeetService;
use App\Utils\GoogleOAuthService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;





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
public function new(
    Request $request, 
    EntityManagerInterface $em,
    ValidatorInterface $validator,
    GoogleMeetService $googleMeetService,
    LoggerInterface $logger
): Response {
    $interview = new Interview();
    $form = $this->createForm(InterviewType::class, $interview);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        $errors = $validator->validate($interview);
        
        if (count($errors) === 0 && $form->isValid()) {
            try {
                // Copie le titre de l'offre
                $interview->setTitreoffre($interview->getIdoffre()->getTitreoffre());
                
                // Gestion Google Meet
                if ($interview->getTypeinterview() === TypeInterview::ENLIGNE) {
                    try {
                        $interview->setLocalisation(null);
                        $date = $interview->getDateinterview();
                        $time = $interview->getTimeinterview();
                        
                        $startDateTime = new \DateTime($date->format('Y-m-d') . ' ' . $time->format('H:i:s'));
                        $endDateTime = clone $startDateTime;
                        $endDateTime->modify('+1 hour');

                        try {
                            $meetLink = $googleMeetService->createMeetLink($startDateTime, $endDateTime);
                            $interview->setLienmeet($meetLink);
                            $logger->info('Meet link created', ['link' => $meetLink]);
                        } catch (\Exception $e) {
                            $logger->error('Google Meet error', ['error' => $e]);
                            $this->addFlash('warning', 'Redirection vers Google pour authentification...');
                            return $this->redirectToRoute('google_auth_callback');
                        }
                    } catch (\Exception $e) {
                        $logger->error('Meet creation failed', ['error' => $e->getMessage()]);
                        $this->addFlash('error', 'Erreur Meet: ' . $e->getMessage());
                        return $this->redirectToRoute('app_interview_new');
                    }
                } else {
                    $interview->setLienmeet(null);
                }

                $em->persist($interview);
                $em->flush();

                $logger->info('Interview saved', [
                    'id' => $interview->getIdinterview(),
                    'meet_link' => $interview->getLienmeet()
                ]);
                
                $this->addFlash('success', 'Interview créée avec succès!');
                return $this->redirectToRoute('app_interview_index');

            } catch (\Exception $e) {
                $logger->error('Error saving interview', ['error' => $e->getMessage()]);
                $this->addFlash('error', 'Erreur: ' . $e->getMessage());
            }
        } else {
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
    }

    return $this->render('interview/new.html.twig', [
        'form' => $form->createView(),
        'interview' => $interview,
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
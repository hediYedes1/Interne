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
use App\Utils\InterviewStatisticsService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;





use App\Entity\Affectationinterview;
use App\Repository\AffectationinterviewRepository;

#[Route('/interview')]
final class InterviewController extends AbstractController
{
// src/Controller/InterviewController.php

#[Route('/list', name: 'app_interview_index', methods: ['GET'])]
public function index(
    Request $request,
    InterviewRepository $interviewRepository,
    PaginatorInterface $paginator
): Response {
    $user = $this->getUser();
    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos interviews.');
    }

    $titre = $request->query->get('titreoffre');
    $type = $request->query->get('typeinterview');

    // Requête personnalisée filtrée par utilisateur + titre + type
    $query = $interviewRepository->findInterviewsByUserWithFilters($user, $titre, $type);

    // Pagination
    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('interview/index.html.twig', [
        'pagination' => $pagination,
    ]);
}


#[Route('/listBack', name: 'app_interview_index_back', methods: ['GET'])]
public function indexBack(Request $request, InterviewRepository $interviewRepository, PaginatorInterface $paginator): Response
{
    $user = $this->getUser();
    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos interviews.');
    }

    $titre = $request->query->get('titreoffre');
    $type = $request->query->get('typeinterview');

    $query = $interviewRepository->findInterviewsByUserWithFilters($user, $titre, $type);

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('interview/indexBack.html.twig', [
        'pagination' => $pagination,
    ]);
}


#[Route('/listFront', name: 'app_interview_index_front', methods: ['GET'])]
public function indexFront(Request $request, InterviewRepository $interviewRepository, PaginatorInterface $paginator): Response
{
    $user = $this->getUser();
    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos interviews.');
    }

    $titre = $request->query->get('titreoffre');
    $type = $request->query->get('typeinterview');

    $query = $interviewRepository->findInterviewsByUserWithFilters($user, $titre, $type);

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('interview/indexFront.html.twig', [
        'pagination' => $pagination,
    ]);
}

#[Route('/new', name: 'app_interview_new', methods: ['GET', 'POST'])]
public function new(
    Request $request, 
    EntityManagerInterface $em,
    GoogleMeetService $googleMeetService,
    LoggerInterface $logger
): Response {
    $user = $this->getUser();
    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour créer une interview.');
    }

    $interview = new Interview();
    $form = $this->createForm(InterviewType::class, $interview);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            // Copie le titre de l'offre
            $interview->setTitreoffre($interview->getIdoffre()->getTitreoffre());
            
            // Gestion Google Meet
            if ($interview->getTypeinterview() === TypeInterview::ENLIGNE) {
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
            } else {
                $interview->setLienmeet(null);
            }

            // Création de l'affectation
            $affectation = new Affectationinterview();
            $affectation->setIdutilisateur($user);
            $affectation->setIdinterview($interview);
            $affectation->setDateaffectationinterview(new \DateTime());
            
            $em->persist($interview);
            $em->persist($affectation);
            $em->flush();

            $logger->info('Interview saved', [
                'id' => $interview->getIdinterview(),
                'meet_link' => $interview->getLienmeet()
            ]);
            
            $this->addFlash('success', 'Interview créée avec succès!');
            return $this->redirectToRoute('app_interview_index');

        } catch (\Exception $e) {
            $logger->error('Error saving interview', ['error' => $e->getMessage()]);
            $this->addFlash('error', 'Une erreur est survenue lors de la création de l\'interview: ' . $e->getMessage());
        }
    } elseif ($form->isSubmitted()) {
        // Gestion des erreurs de validation du formulaire
        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
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
    public function edit(
        Request $request, 
        Interview $interview, 
        EntityManagerInterface $entityManager
    ): Response {
        // Vérification de l'authentification et des permissions
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour modifier une interview.');
        }
    
        // Vérification que l'utilisateur est bien associé à cette interview
        $affectation = $entityManager->getRepository(Affectationinterview::class)->findOneBy([
            'idinterview' => $interview,
            'idutilisateur' => $user
        ]);
    
        if (!$affectation) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette interview.');
        }
    
        // Sauvegarde de l'ancien type avant modification
        $oldType = $interview->getTypeinterview();
        
        $form = $this->createForm(InterviewType::class, $interview);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Mise à jour du titre de l'offre si nécessaire
                $interview->setTitreoffre($interview->getIdoffre()->getTitreoffre());
                
                // Gestion du changement de type d'interview
                $newType = $interview->getTypeinterview();
                
                if ($oldType !== $newType) {
                    if ($newType === TypeInterview::ENLIGNE) {
                        $interview->setLocalisation(null);
                        // Ici vous pourriez ajouter la génération d'un nouveau lien Meet si nécessaire
                    } else {
                        $interview->setLienmeet(null);
                    }
                } else {
                    // Cohérence des champs même si le type n'a pas changé
                    if ($newType === TypeInterview::ENLIGNE) {
                        $interview->setLocalisation(null);
                    } else {
                        $interview->setLienmeet(null);
                    }
                }
                
                // Mise à jour de la date de modification dans l'affectation
                $affectation->setDateaffectationinterview(new \DateTime());
                
                $entityManager->flush();
    
                $this->addFlash('success', 'Interview modifiée avec succès!');
                return $this->redirectToRoute('app_interview_index', [], Response::HTTP_SEE_OTHER);
                
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification: ' . $e->getMessage());
            }
        }
    
        return $this->render('interview/edit.html.twig', [
            'interview' => $interview,
            'form' => $form->createView(),
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
   
    #[Route('/interview/statistics', name: 'app_interview_stats', methods: ['GET'])]
    public function statistics(InterviewStatisticsService $statisticsService): Response
    {
        $stats = $statisticsService->getInterviewStatistics();
        
        return $this->render('interview/stat.html.twig', [
            'statistics' => $stats
        ]);
    }
}
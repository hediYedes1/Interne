<?php

namespace App\Controller;

use App\Entity\Testtechnique;
use App\Entity\Interview;
use App\Entity\QuizQuestion;
use App\Utils\QuizApiService;
use App\Form\TesttechniqueType;
use App\Repository\TesttechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enum\StatutTestTechnique;
use Symfony\Component\Routing\Attribute\Route;
use App\Utils\EmailService;

#[Route('/testtechnique')]
final class TesttechniqueController extends AbstractController
{
    #[Route('/list', name: 'app_testtechnique_index', methods: ['GET'])]
    public function index(TesttechniqueRepository $testtechniqueRepository, Request $request): Response
    {
        $titre = $request->query->get('titretesttechnique');
        $statut = $request->query->get('statuttesttechnique');

        $testtechniques = $testtechniqueRepository->findByFilters($titre, $statut);
            

        return $this->render('testtechnique/index.html.twig', [
            'testtechniques' => $testtechniques,
        ]);
    }

    #[Route('/listBack', name: 'app_testtechnique_index_back', methods: ['GET'])]
    public function indexBack(TesttechniqueRepository $testtechniqueRepository, Request $request): Response
    {
        $titre = $request->query->get('titretesttechnique');
        $statut = $request->query->get('statuttesttechnique');

        $testtechniques = $testtechniqueRepository->findByFilters($titre, $statut);
            

        return $this->render('testtechnique/indexBack.html.twig', [
            'testtechniques' => $testtechniques,
        ]);
    }

    #[Route('/listFront', name: 'app_testtechnique_index_front', methods: ['GET'])]
    public function indexFront(TesttechniqueRepository $testtechniqueRepository, Request $request): Response
    {
        $titre = $request->query->get('titretesttechnique');
        $statut = $request->query->get('statuttesttechnique');

        $testtechniques = $testtechniqueRepository->findByFilters($titre, $statut);
            

        return $this->render('testtechnique/indexFront.html.twig', [
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
        'testtechnique' => $testtechnique
    ]);
}

#[Route('/{idtesttechnique}/back', name: 'app_testtechnique_show_back', methods: ['GET'])]
public function showBack(Testtechnique $testtechnique): Response
{
    return $this->render('testtechnique/showBack.html.twig', [
        'testtechnique' => $testtechnique
    ]);
}

#[Route('/{idtesttechnique}/front', name: 'app_testtechnique_show_front', methods: ['GET'])]
public function showFront(Testtechnique $testtechnique): Response
{
    return $this->render('testtechnique/showFront.html.twig', [
        'testtechnique' => $testtechnique
    ]);
}

    #[Route('/{idtesttechnique}/edit', name: 'app_testtechnique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testtechnique $testtechnique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TesttechniqueType::class, $testtechnique);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_testtechnique_by_interview', [
                'idinterview' => $testtechnique->getIdinterview()->getIdinterview()
            ], Response::HTTP_SEE_OTHER);
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
 
#[Route('/interview/{idinterview}/tests', name: 'app_testtechnique_by_interview', methods: ['GET'])]
public function indexByInterview(
    Interview $idinterview, 
    TesttechniqueRepository $testtechniqueRepository, 
    Request $request
): Response {
    $titre = $request->query->get('titretesttechnique');
    $statut = $request->query->get('statuttesttechnique');

    
    $testtechniques = $testtechniqueRepository->findByFiltersForInterview(
        $idinterview, 
        $titre, 
        $statut
    );

    return $this->render('testtechnique/index.html.twig', [
        'testtechniques' => $testtechniques,
        'interview' => $idinterview,
    ]);
}

#[Route('/interview/{idinterview}/testsBack', name: 'app_testtechnique_by_interview_back', methods: ['GET'])]
public function indexByInterviewBack(
    Interview $idinterview, 
    TesttechniqueRepository $testtechniqueRepository, 
    Request $request
): Response {
    $titre = $request->query->get('titretesttechnique');
    $statut = $request->query->get('statuttesttechnique');

    
    $testtechniques = $testtechniqueRepository->findByFiltersForInterview(
        $idinterview, 
        $titre, 
        $statut
    );

    return $this->render('testtechnique/indexBack.html.twig', [
        'testtechniques' => $testtechniques,
        'interview' => $idinterview,
    ]);
}

#[Route('/interview/{idinterview}/testsFront', name: 'app_testtechnique_by_interview_front', methods: ['GET'])]
public function indexByInterviewFront(
    Interview $idinterview, 
    TesttechniqueRepository $testtechniqueRepository, 
    Request $request
): Response {
    $titre = $request->query->get('titretesttechnique');
    $statut = $request->query->get('statuttesttechnique');

    // Modifier la méthode findByFilters dans le repository pour prendre en compte l'interview
    $testtechniques = $testtechniqueRepository->findByFiltersForInterview(
        $idinterview, 
        $titre, 
        $statut
    );

    return $this->render('testtechnique/indexFront.html.twig', [
        'testtechniques' => $testtechniques,
        'interview' => $idinterview,
    ]);
}

#[Route('/new/{idinterview}', name: 'app_testtechnique_new_for_interview', methods: ['GET', 'POST'])]
public function newForInterview(Request $request, EntityManagerInterface $entityManager, Interview $idinterview): Response
{
    $testtechnique = new Testtechnique();
    $testtechnique->setIdinterview($idinterview);

    $form = $this->createForm(TesttechniqueType::class, $testtechnique, [
        'interview' => $idinterview, 
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($testtechnique);
        $entityManager->flush();

        return $this->redirectToRoute('app_testtechnique_by_interview', [
            'idinterview' => $idinterview->getIdinterview()
        ], Response::HTTP_SEE_OTHER);
    }

    return $this->render('testtechnique/new.html.twig', [
        'form' => $form,
        'interview' => $idinterview,
        'testtechnique' => $testtechnique
    ]);
}
#[Route('/{idtesttechnique}/add-quiz', name: 'app_testtechnique_add_quiz', methods: ['GET', 'POST'])]
public function addQuiz(
    Request $request,
    Testtechnique $testtechnique,
    QuizApiService $quizApiService,
    EntityManagerInterface $entityManager
): Response {
    if ($request->isMethod('POST')) {
        $category = $request->request->get('category');
        $difficulty = $request->request->get('difficulty');
        $limit = $request->request->get('limit', 10);

        try {
            $questions = $quizApiService->fetchQuizQuestions($category, $difficulty, $limit);
            
            
            $storableQuestions = [];
            foreach ($questions as $question) {
                $questionData = [
                    'question' => $question->getQuestion(),
                    'answers' => $question->getAnswers(),
                    'correctAnswers' => $question->getCorrectAnswers(),
                    'category' => $question->getCategory(),
                    'difficulty' => $question->getDifficulty()
                ];
                
           
                if (method_exists($question, 'getDescription')) {
                    $questionData['description'] = $question->getDescription();
                }
                if (method_exists($question, 'getExplanation')) {
                    $questionData['explanation'] = $question->getExplanation();
                }
                if (method_exists($question, 'getTip')) {
                    $questionData['tip'] = $question->getTip();
                }
                
                $storableQuestions[] = $questionData;
            }
            
            $testtechnique->setQuestions($storableQuestions);
            $entityManager->flush();

            $this->addFlash('success', 'Quiz ajouté avec succès au test technique');
            return $this->redirectToRoute('app_testtechnique_show', [
                'idtesttechnique' => $testtechnique->getIdtesttechnique()
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la récupération du quiz: '.$e->getMessage());
        }
    }

    return $this->render('testtechnique/add_quiz.html.twig', [
        'testtechnique' => $testtechnique,
        'categories' => [
            'Linux', 'Bash', 'PHP', 'Docker', 'MySQL', 
            'WordPress', 'HTML', 'JavaScript', 'CSS'
        ],
        'difficulties' => ['Easy', 'Medium', 'Hard']
    ]);
}
    #[Route('/{idtesttechnique}/clear-quiz', name: 'app_testtechnique_clear_quiz', methods: ['POST'])]
    public function clearQuiz(
        Testtechnique $testtechnique,
        EntityManagerInterface $entityManager
    ): Response {
        $testtechnique->setQuestions([]);
        $testtechnique->setStatuttesttechnique(StatutTestTechnique::ENATTENTE);
        $entityManager->flush();

        $this->addFlash('success', 'Quiz supprimé du test technique');
        return $this->redirectToRoute('app_testtechnique_show', [
            'idtesttechnique' => $testtechnique->getIdtesttechnique()
        ]);
    }
#[Route('/{idtesttechnique}/quiz', name: 'app_testtechnique_quiz', methods: ['GET'])]
public function showQuiz(Testtechnique $testtechnique): Response
{
    if (!$testtechnique->getQuestions()) {
        $this->addFlash('warning', 'Ce test technique n\'a pas de quiz associé');
        return $this->redirectToRoute('app_testtechnique_show', [
            'idtesttechnique' => $testtechnique->getIdtesttechnique()
        ]);
    }

    return $this->render('testtechnique/show_quiz.html.twig', [
        'testtechnique' => $testtechnique
    ]);
}
#[Route('/{idtesttechnique}/quizFront', name: 'app_testtechnique_quiz_front', methods: ['GET'])]
public function showQuizFront(Testtechnique $testtechnique): Response
{
    if (!$testtechnique->getQuestions()) {
        $this->addFlash('warning', 'Ce test technique n\'a pas de quiz associé');
        return $this->redirectToRoute('app_testtechnique_show_front', [
            'idtesttechnique' => $testtechnique->getIdtesttechnique()
        ]);
    }

    return $this->render('testtechnique/show_quizFront.html.twig', [
        'testtechnique' => $testtechnique
    ]);
}
#[Route('/quiz/result/{id}', name: 'quiz_result')]
public function showResult(Testtechnique $test , QuizQuestion $quiz): Response
{
    return $this->render('quiz/result.html.twig', [
        'test' => $test,
        'score' => $quiz->$test->getScore(), 
        'statut' => $test->getStatuttesttechnique()
    ]);
}
}

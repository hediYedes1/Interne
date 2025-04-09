<?php

namespace App\Controller;

use App\Entity\TestTechnique;
use App\Repository\TesttechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Enum\StatutTestTechnique;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/quiz/submit', name: 'quiz_submit', methods: ['POST'])]
    public function submitQuiz(Request $request, TesttechniqueRepository $testRepo, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = $request->getContentType() === 'json' 
                ? json_decode($request->getContent(), true) 
                : $request->request->all();
    
            if (!$data) {
                throw new \Exception('Données invalides');
            }
    
            if (!isset($data['test_id']) || !isset($data['answers'])) {
                throw new \Exception('Données manquantes');
            }
    
            $test = $testRepo->find($data['test_id']);
            if (!$test) {
                throw new \Exception('Test introuvable');
            }
    
            $correctAnswersCount = 0;
            $questions = $test->getQuestions();
            
            foreach ($questions as $index => $question) {
                if (!isset($data['answers'][$index])) {
                    continue;
                }
                
                $userAnswer = $data['answers'][$index];
                $correctAnswers = $question['correctAnswers'] ?? [];
                
                if (isset($correctAnswers[$userAnswer]) && $correctAnswers[$userAnswer]) {
                    $correctAnswersCount++;
                }
            }
    
            // Mise à jour du statut uniquement
            $test->setStatuttesttechnique($correctAnswersCount >= 8 ? StatutTestTechnique::ACCEPTE : StatutTestTechnique::REFUSE);
            $em->flush();
    
            return new JsonResponse([
                'success' => true,
                'score' => $correctAnswersCount, // Retourné pour affichage seulement
                'total' => count($questions),
                'statut' => $test->getStatuttesttechnique(),
                'message' => 'Résultats enregistrés'
            ]);
    
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}

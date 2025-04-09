<?php
// src/Controller/QuizController.php

namespace App\Controller;

use App\Entity\TestTechnique;
use App\Entity\QuizQuestion;
use App\Repository\TestTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\QuizApiService;

class QuizController extends AbstractController
{
    #[Route('/quiz/submit', name: 'quiz_submit', methods: ['POST', 'GET'])]
    public function submitQuiz(Request $request, TestTechniqueRepository $testRepo, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = $request->getMethod() === 'POST' 
            ? json_decode($request->getContent(), true) 
            : $request->query->all();
            
            if (!$data) {
                throw new \Exception('Invalid JSON data');
            }
    
            if (!isset($data['test_id'])) {
                throw new \Exception('Missing test_id');
            }
    
            $test = $testRepo->find($data['test_id']);
            if (!$test) {
                throw new \Exception('Test not found');
            }
    
            $score = 0;
            $questions = $test->getQuestions();
            
            foreach ($questions as $index => $question) {
                if (!isset($data['answers'][$index])) 
                    continue;
                
                
                $userAnswer = $data['answers'][$index];
                $correctAnswers = $question['correctAnswers'];
                error_log("Question $index - Réponse utilisateur: $userAnswer");
                error_log("Bonnes réponses: " . print_r($correctAnswers, true));
                
                // Vérification simplifiée
                if (is_array($correctAnswers)) {
                    // Si la réponse de l'utilisateur est dans le tableau des bonnes réponses
                    if (in_array($userAnswer, array_keys($correctAnswers))) {
                        $score++;
                    }
                }
            } // <-- Cette accolade fermante manquait dans votre code original
            
            $test->setStatut($score >= 8 ? 'ACCEPTE' : 'REFUSE');
            $test->setScore($score);
            $em->flush();
    
            return new JsonResponse([
                'success' => true,
                'score' => $score,
                'total' => count($questions),
                'statut' => $test->getStatut(),
                'message' => 'Quiz submitted successfully'
            ]);
    
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
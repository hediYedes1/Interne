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
            $data = json_decode($request->getContent(), true) ?: $request->request->all();
            
            if (!$data) throw new \Exception('Aucune donnée reçue');
            if (!isset($data['test_id'])) throw new \Exception('ID de test manquant');
            if (!isset($data['answers'])) throw new \Exception('Réponses manquantes');
    
            $test = $testRepo->find($data['test_id']);
            if (!$test) throw new \Exception('Test non trouvé');
    
            $correctCount = 0;
            $questions = $test->getQuestions(); // Cette méthode décode déjà le JSON
    
            foreach ($questions as $index => $question) {
                if (!isset($data['answers'][$index])) continue;
            
                $userAnswer = $data['answers'][$index];
                $correctAnswers = $question['correct_answers'] ?? [];
            
                $userAnswerKey = strtolower($userAnswer);
            
                if (array_key_exists($userAnswerKey, $correctAnswers)) {
                    $isCorrect = filter_var($correctAnswers[$userAnswerKey], FILTER_VALIDATE_BOOLEAN);
                    if ($isCorrect) {
                        $correctCount++;
                    }
                }
                // Debug crucial
                error_log("Question {$index}:");
                error_log("Réponse utilisateur: {$userAnswer}");
                error_log("Bonnes réponses: ".print_r($correctAnswers, true));
    
                // Vérification robuste
                if (array_key_exists($userAnswer, $correctAnswers)) {
                    $isCorrect = $correctAnswers[$userAnswer];
                    if ($isCorrect === true || $isCorrect === 'true' || $isCorrect === 1) {
                        $correctCount++;
                        error_log("Bonne réponse!");
                    }
                }
            }
    
            $statut = $correctCount >= 8 ? StatutTestTechnique::ACCEPTE : StatutTestTechnique::REFUSE;
            $test->setStatuttesttechnique($statut);
            $em->flush();
    
            return new JsonResponse([
                'success' => true,
                'score' => $correctCount,
                'total' => count($questions),
                'statut' => $statut->value,
                'message' => 'Quiz évalué avec succès'
            ]);
    
        } catch (\Exception $e) {
            error_log("ERREUR: ".$e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
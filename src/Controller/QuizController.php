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
            $questions = $test->getQuestions();
    
            foreach ($questions as $index => $question) {
                if (!isset($data['answers'][$index])) continue;
    
                $userAnswer = $data['answers'][$index];
                $correctAnswers = $question['correctAnswers'] ?? [];
    
                // Vérification plus robuste des réponses
                foreach ($correctAnswers as $answerKey => $isCorrect) {
                    // Normaliser les clés pour la comparaison
                    $normalizedUserAnswer = strtolower(trim($userAnswer));
                    $normalizedAnswerKey = strtolower(trim(str_replace('_correct', '', $answerKey)));
    
                    if ($normalizedUserAnswer === $normalizedAnswerKey) {
                        if (filter_var($isCorrect, FILTER_VALIDATE_BOOLEAN)) {
                            $correctCount++;
                            break; // Sortir de la boucle si une bonne réponse est trouvée
                        }
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
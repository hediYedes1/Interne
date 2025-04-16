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
use Symfony\Component\HttpFoundation\Response;

class QuizController extends AbstractController
{
    #[Route('/quiz/submit', name: 'quiz_submit', methods: ['POST'])]
    public function submitQuiz(Request $request, TesttechniqueRepository $testRepo, EntityManagerInterface $em): Response
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
                $userAnswer = $data['answers'][$index] ?? null;
                $correctAnswers = $question['correctAnswers'] ?? [];
                $correctAnswerKey = null;
    
                // Trouver la bonne réponse
                foreach ($correctAnswers as $answerKey => $isCorrectAnswer) {
                    if (filter_var($isCorrectAnswer, FILTER_VALIDATE_BOOLEAN)) {
                        $correctAnswerKey = str_replace('_correct', '', $answerKey);
                        break;
                    }
                }
    
                // Vérifier si la réponse de l'utilisateur est correcte
                if ($userAnswer) {
                    $normalizedUserAnswer = strtolower(trim($userAnswer));
                    $normalizedCorrectAnswer = strtolower(trim($correctAnswerKey));
                    
                    if ($normalizedUserAnswer === $normalizedCorrectAnswer) {
                        $correctCount++;
                    }
                }
            }
    
            $statut = $correctCount >= 8 ? StatutTestTechnique::ACCEPTE : StatutTestTechnique::REFUSE;
            $test->setStatuttesttechnique($statut);
            $em->flush();
    
            $this->addFlash('success', 'Quiz soumis avec succès. Résultat: ' . $statut->value);
            return $this->redirectToRoute('app_testtechnique_show', [
                'idtesttechnique' => $test->getIdtesttechnique()
            ]);
    
        } catch (\Exception $e) {
            error_log("ERREUR: ".$e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_testtechnique_quiz', [
                'idtesttechnique' => $data['test_id'] ?? null
            ]);
        }
    }

    #[Route('/quiz/submitFront', name: 'quiz_submit_front', methods: ['POST'])]
    public function submitQuizFront(Request $request, TesttechniqueRepository $testRepo, EntityManagerInterface $em): Response
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
                $userAnswer = $data['answers'][$index] ?? null;
                $correctAnswers = $question['correctAnswers'] ?? [];
                $correctAnswerKey = null;
    
                // Trouver la bonne réponse
                foreach ($correctAnswers as $answerKey => $isCorrectAnswer) {
                    if (filter_var($isCorrectAnswer, FILTER_VALIDATE_BOOLEAN)) {
                        $correctAnswerKey = str_replace('_correct', '', $answerKey);
                        break;
                    }
                }
    
                // Vérifier si la réponse de l'utilisateur est correcte
                if ($userAnswer) {
                    $normalizedUserAnswer = strtolower(trim($userAnswer));
                    $normalizedCorrectAnswer = strtolower(trim($correctAnswerKey));
                    
                    if ($normalizedUserAnswer === $normalizedCorrectAnswer) {
                        $correctCount++;
                    }
                }
            }
    
            $statut = $correctCount >= 8 ? StatutTestTechnique::ACCEPTE : StatutTestTechnique::REFUSE;
            $test->setStatuttesttechnique($statut);
            $em->flush();
    
            $this->addFlash('success', 'Quiz soumis avec succès. Résultat: ' . $statut->value);
            return $this->redirectToRoute('app_testtechnique_show_front', [
                'idtesttechnique' => $test->getIdtesttechnique()
            ]);
    
        } catch (\Exception $e) {
            error_log("ERREUR: ".$e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_testtechnique_quiz_front', [
                'idtesttechnique' => $data['test_id'] ?? null
            ]);
        }
    }
}
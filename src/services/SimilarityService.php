<?php
// src/services/SimilarityService.php

namespace App\services;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use App\services\CVParserService;

class SimilarityService
{
    private const API_URL = 'https://api.apilayer.com/nlp/similarity';
    private const API_KEY = '7tK0n4tCBEYv0CEBxnGo0HW5BCeAfwsk';

    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private CVParserService $cvParserService;

    public function __construct(
        HttpClientInterface $httpClient, 
        LoggerInterface $logger,
        CVParserService $cvParserService
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->cvParserService = $cvParserService;
    }

    public function calculateSimilarity(string $cvText, string $descriptionText): float
    {
        try {
            // Nettoyage des textes
            $cleanCv = $this->cvParserService->cleanText($cvText);
            $cleanDescription = $this->cvParserService->cleanText($descriptionText);

            $this->logger->info('Cleaned CV Text:', ['cleaned_cv' => $cleanCv]);
            $this->logger->info('Cleaned Description Text:', ['cleaned_description' => $cleanDescription]);

            // Conversion en tableaux de mots uniques
            $cvWords = array_unique(explode(' ', $cleanCv));
            $descWords = array_unique(explode(' ', $cleanDescription));
            
            // Calcul du pourcentage de correspondance des mots
            $matchingWords = array_intersect($descWords, $cvWords);
            $matchCount = count($matchingWords);
            $totalDescWords = count($descWords);
            
            $wordMatchPercent = ($totalDescWords > 0) ? ($matchCount / $totalDescWords) * 100 : 0;
            
            // Si correspondance parfaite, retourner 100%
            if ($wordMatchPercent >= 100) {
                $this->logger->info('All description words found in CV - returning 100%');
                return 100.00;
            }
            
            // Obtenir le score de similarité sémantique de l'API
            $apiScore = $this->getApiSimilarityScore($cleanCv, $cleanDescription);
            
            // Calcul du score final (70% correspondance mots, 30% API)
            $finalScore = ($wordMatchPercent * 0.7) + ($apiScore * 0.3);
            
            $this->logger->info('Similarity calculation results', [
                'word_match_percent' => $wordMatchPercent,
                'api_score' => $apiScore,
                'final_score' => $finalScore,
                'matching_words' => $matchingWords,
                'missing_words' => array_diff($descWords, $cvWords)
            ]);

            return min(round($finalScore, 2), 100.00);

        } catch (\Exception $e) {
            $this->logger->error('Similarity calculation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
        }
    }

    private function getApiSimilarityScore(string $cleanCv, string $cleanDescription): float
    {
        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'apikey' => self::API_KEY,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'text1' => $cleanCv,
                    'text2' => $cleanDescription,
                ],
                'timeout' => 30.0,
            ]);
            $content = $response->toArray();
            
            if (!isset($content['result'])) {
                $this->logger->error('API response missing result field', ['response' => $content]);
                return 0;
            }

            return (float) $content['result'] * 100;

        } catch (\Exception $e) {
            $this->logger->error('API request failed', [
                'error' => $e->getMessage(),
                'cv_sample' => substr($cleanCv, 0, 100) . '...',
                'desc_sample' => substr($cleanDescription, 0, 100) . '...'
            ]);
            return 0;
        }
    }
}
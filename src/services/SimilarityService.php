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

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, CVParserService $cvParserService)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->cvParserService = $cvParserService;
    }

    public function calculateSimilarity(string $cvText, string $descriptionText): float
    {
        try {
            // Nettoyage avancé des deux textes (CV + description)
            $cleanCv = $this->cvParserService->cleanText($cvText);
            $cleanDescription = $this->cvParserService->cleanText($descriptionText);

            // Appel à l'API NLP
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'apikey' => self::API_KEY,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'text1' => $cleanCv,
                    'text2' => $cleanDescription,
                ],
                'timeout' => 60.0,
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->toArray();

            if ($statusCode !== 200) {
                $this->logger->error('API error', [
                    'statusCode' => $statusCode,
                    'response' => $content,
                ]);
                return 0;
            }

            if (!isset($content['result'])) {
                $this->logger->error('Result key not found in response', ['response' => $content]);
                return 0;
            }

            // Récupération du score de similarité (entre 0 et 1)
            $apiScore = (float) $content['result'];

            // Transformation en tableaux de mots uniques
            $cvWordsArray = array_unique(explode(' ', $cleanCv));
            $descriptionWordsArray = array_unique(explode(' ', $cleanDescription));

            // Comparaison des mots
            $matchingWords = array_intersect($descriptionWordsArray, $cvWordsArray);
            $allWordsMatched = count($descriptionWordsArray) > 0 && count($matchingWords) === count($descriptionWordsArray);

            // Forcer à 100% uniquement si tous les mots sont présents et que le score est < 1.0
            if ($allWordsMatched && $apiScore < 1.0) {
                $this->logger->info('Forcing score to 1.0 (100%) as all description words are matched in CV.');
                $apiScore = 1.0;
            }

            // Conversion finale en pourcentage
            $percentage = round($apiScore * 100, 2);
            return min($percentage, 100.00);

        } catch (\Exception $e) {
            $this->logger->error('API request failed', [
                'error' => $e->getMessage(),
                'cvText' => $cvText,
                'descriptionText' => $descriptionText,
            ]);
            return 0;
        }
    }
}
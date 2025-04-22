<?php
// src/services/SimilarityService.php

namespace App\services;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

class SimilarityService
{
    private const API_URL = 'https://text-similarity-calculator.p.rapidapi.com/stringcalculator.php';
    private const API_KEY = '4d094fdadcmsh2a5694cd02c49f9p1e0a66jsne4db56ca4296';
    private const MAX_TEXT_LENGTH = 5000;
    private const MAX_RETRIES = 2;

    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function calculateSimilarity(string $cvText, string $offreDescription): float
    {
        $retryCount = 0;
        
        while ($retryCount <= self::MAX_RETRIES) {
            try {
                // Preprocess texts
                $cvText = $this->preprocessText($cvText);
                $offreDescription = $this->preprocessText($offreDescription);

                // Make API request
                $response = $this->httpClient->request('GET', self::API_URL, [
                    'query' => [
                        'ftext' => substr($cvText, 0, self::MAX_TEXT_LENGTH),
                        'stext' => substr($offreDescription, 0, self::MAX_TEXT_LENGTH)
                    ],
                    'headers' => [
                        'X-RapidAPI-Host' => 'text-similarity-calculator.p.rapidapi.com',
                        'X-RapidAPI-Key' => self::API_KEY,
                        'Accept' => 'application/json'
                    ],
                    'timeout' => 15
                ]);

                $statusCode = $response->getStatusCode();
                $content = $response->getContent();

                // Debug logging
                $this->logger->debug('Similarity API Response', [
                    'status' => $statusCode,
                    'response' => $content
                ]);

                if ($statusCode !== 200) {
                    throw new \RuntimeException("API returned status code: $statusCode");
                }

                // Parse response (the API returns a plain number as string)
                $similarity = (float) $content;

                if ($similarity < 0 || $similarity > 100) {
                    throw new \RuntimeException("Invalid similarity value: $similarity");
                }

                return $similarity;

            } catch (ClientException $e) {
                $responseContent = $e->getResponse()->getContent(false);
                $this->logger->error('API Client Error', [
                    'error' => $e->getMessage(),
                    'response' => $responseContent,
                    'retry_count' => $retryCount
                ]);

                if ($retryCount === self::MAX_RETRIES) {
                    throw new \RuntimeException('Service de similarité temporairement indisponible');
                }
                
                $retryCount++;
                sleep(1); // Wait before retrying
                
            } catch (\Exception $e) {
                $this->logger->error('Similarity calculation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \RuntimeException('Erreur lors du calcul de similarité');
            }
        }
        
        throw new \RuntimeException('Échec après plusieurs tentatives');
    }

    private function preprocessText(string $text): string
    {
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');
        
        // Remove special characters but keep accented characters
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        
        // Replace multiple spaces with single space
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
}
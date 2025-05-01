<?php
namespace App\Utils;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class GrammarCheckerService
{
    private $client;
    private $logger;
    private $apiUrl;
    private $apiKey;
    private $apiHost;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->apiUrl = 'https://grammer-checker1.p.rapidapi.com/v1/grammer-checker';
        $this->apiKey = '0aa6a48b41msh412fa24b471d7e4p11376djsn2be804473491';
        $this->apiHost = 'grammer-checker1.p.rapidapi.com';
    }

    public function checkGrammar(string $text): array
    {
        // Normaliser l'encodage du texte
        $text = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text));
        $this->logger->info('Début de la vérification grammaticale', ['text' => $text]);
        
        // Pré-vérification des erreurs courantes
        $preCheckResult = $this->preCheckGrammar($text);
        $this->logger->info('Pré-vérification terminée', [
            'errors_count' => count($preCheckResult['errors']['details'])
        ]);

        try {
            $requestPayload = [
                'text' => $text,
                'language' => 'fr',
                'strictness' => 'strict'
            ];

            $this->logger->debug('Envoi de la requête à l\'API', [
                'url' => $this->apiUrl,
                'headers' => [
                    'X-RapidAPI-Key' => '*'.substr($this->apiKey, -4),
                    'X-RapidAPI-Host' => $this->apiHost
                ],
                'payload' => $requestPayload,
                'text_length' => mb_strlen($text)
            ]);

            $response = $this->client->request('POST', $this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-RapidAPI-Key' => $this->apiKey,
                    'X-RapidAPI-Host' => $this->apiHost
                ],
                'json' => $requestPayload,
                'timeout' => 10
            ]);

            $statusCode = $response->getStatusCode();
            $responseHeaders = $response->getHeaders();
            $responseContent = $response->getContent(false);

            $this->logger->debug('Réponse de l\'API', [
                'status' => $statusCode,
                'headers' => $responseHeaders,
                'response_time' => $responseHeaders['x-response-time'][0] ?? null,
                'content_length' => strlen($responseContent)
            ]);

            if ($statusCode !== 200) {
                $this->logger->error('Erreur de l\'API', [
                    'status' => $statusCode,
                    'content' => $responseContent,
                    'request_payload' => $requestPayload
                ]);
                throw new \RuntimeException("Erreur API: statut $statusCode");
            }

            $this->logger->debug('Contenu brut de la réponse API', ['content' => $responseContent]);
            
            $apiResult = $this->processApiResponse($response, $text);
            
            // Fusionner les résultats de la pré-vérification et de l'API
            $finalResult = $this->mergeResults($preCheckResult, $apiResult);
            
            $this->logger->info('Résultat final de la vérification', [
                'errors_count' => count($finalResult['errors']['details']),
                'original_length' => mb_strlen($text),
                'corrected_length' => mb_strlen($finalResult['errors']['correction']),
                'correction_ratio' => $finalResult['errors']['details'] ? 
                    round(count($finalResult['errors']['details'])/mb_strlen($text)*100, 2).'%' : '0%'
            ]);
            
            return $finalResult;

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la vérification grammaticale', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'text_sample' => mb_substr($text, 0, 50).(mb_strlen($text) > 50 ? '...' : '')
            ]);
            
            // Retourner au moins les erreurs de pré-vérification en cas d'échec API
            return $preCheckResult;
        }
    }

    private function preCheckGrammar(string $text): array
    {
        $commonErrors = [
            '/\bvue\b/i' => 'vu',
            '/\bdes personne\b/i' => 'des personnes',
            '/\btres\b/i' => 'très',
            '/\bmotive\b/i' => 'motivées',
            '/\btravaille\b/i' => 'travaillent',
            '/\bequipe\b/i' => 'équipe'
        ];

        $details = [];
        $correctedText = $text;

        foreach ($commonErrors as $pattern => $correction) {
            if (preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $match) {
                    $error = $match[0];
                    $offset = $match[1];
                    $details[] = [
                        'message' => "Faute courante détectée: '$error'",
                        'context' => $this->getErrorContext($text, $offset, strlen($error)),
                        'offset' => $offset,
                        'length' => strlen($error),
                        'rule' => 'Orthographe/Accord',
                        'replacements' => [$correction]
                    ];
                    $correctedText = substr_replace($correctedText, $correction, $offset, strlen($error));
                }
            }
        }

        return [
            'errors' => [
                'error' => $details ? implode("\n", array_column($details, 'message')) : null,
                'correction' => $correctedText,
                'details' => $details
            ]
        ];
    }
/*
    private function processApiResponse($response, string $originalText): array
{
    $content = $response->getContent(false);
    $data = json_decode($content, true);

    if (!isset($data['errors']['correction'])) {
        return $this->postCheckGrammar($originalText);
    }

    $correctedText = $data['errors']['correction'];
    $rawErrors = $data['errors']['error'] ?? '';

    // Nouvelle regex pour le format anglais de l'API
    $details = [];
    if (preg_match_all('/\d+\.\s+"([^"]+)" should be (?:corrected to|")([^"]+)"/i', $rawErrors, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $errorWord = $match[1];
            $correction = $match[2];
            $offset = mb_strpos($originalText, $errorWord);
            
            if ($offset !== false) {
                $context = $this->getErrorContext($originalText, $offset, mb_strlen($errorWord));
                $details[] = [
                    'message' => "Correction suggérée: '$errorWord' → '$correction'",
                    'context' => $context,
                    'offset' => $offset,
                    'length' => mb_strlen($errorWord),
                    'rule' => 'Orthographe/Grammaire',
                    'replacements' => [$correction]
                ];
            }
        }
    }

    return [
        'errors' => [
            'error' => $rawErrors,
            'correction' => $correctedText,
            'details' => $details
        ]
    ];
}
    */
    private function processApiResponse($response, string $originalText): array
    {
        $content = $response->getContent(false);
        $data = json_decode($content, true);
    
        if (!isset($data['errors'])) {
            return $this->postCheckGrammar($originalText);
        }
    
        $correction = $data['errors']['correction'] ?? $originalText;
        $rawError = $data['errors']['error'] ?? '';
    
        // Nouveau traitement pour extraire les erreurs du texte
        $details = [];
        if (!empty($rawError)) {
            // Extraction des erreurs et suggestions
            if (preg_match_all('/\\d+\\.\\s+"([^"]+)" (?:is|are) (?:a )?(?:misspelling|error)[^"]+"([^"]+)"/i', $rawError, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $errorWord = $match[1];
                    $suggestion = $match[2];
                    $offset = mb_strpos($originalText, $errorWord);
                    
                    if ($offset !== false) {
                        $details[] = [
                            'message' => "Faute d'orthographe: '$errorWord' → '$suggestion'",
                            'context' => $this->getErrorContext($originalText, $offset, mb_strlen($errorWord)),
                            'offset' => $offset,
                            'length' => mb_strlen($errorWord),
                            'rule' => 'Orthographe',
                            'replacements' => [$suggestion]
                        ];
                    }
                }
            }
            
            // Si aucune erreur structurée trouvée, on utilise le message brut
            if (empty($details) && !empty($rawError)) {
                $details[] = [
                    'message' => "Correction suggérée",
                    'context' => $originalText,
                    'offset' => 0,
                    'length' => mb_strlen($originalText),
                    'rule' => 'Grammaire',
                    'replacements' => [$correction]
                ];
            }
        }
    
        return [
            'errors' => [
                'error' => $rawError,
                'correction' => $correction,
                'details' => $details
            ]
        ];
    }

    private function postCheckGrammar(string $text): array
    {
        $details = [];
        
        // Vérification des accords
        if (preg_match_all('/\bdes (\w+[^s])\b/i', $text, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $error = $match[0];
                $offset = $match[1];
                $word = $matches[1][0];
                $details[] = [
                    'message' => "Possible erreur de pluriel après 'des': '$word'",
                    'context' => $this->getErrorContext($text, $offset, strlen($error)),
                    'offset' => $offset,
                    'length' => strlen($error),
                    'rule' => 'Accord pluriel',
                    'replacements' => ['des '.$word.'s']
                ];
            }
        }

        return [
            'errors' => [
                'error' => $details ? implode("\n", array_column($details, 'message')) : null,
                'correction' => $text,
                'details' => $details
            ]
        ];
    }

    private function mergeResults(array $preCheck, array $apiResult): array
    {
        // Priorité aux corrections de l'API
        $correction = $apiResult['errors']['correction'] ?? $preCheck['errors']['correction'];
        
        // Fusionner les détails d'erreurs
        $details = array_merge(
            $preCheck['errors']['details'],
            $apiResult['errors']['details']
        );

        // Supprimer les doublons
        $uniqueDetails = [];
        $seenOffsets = [];
        foreach ($details as $detail) {
            $key = $detail['offset'].'-'.$detail['length'];
            if (!isset($seenOffsets[$key])) {
                $uniqueDetails[] = $detail;
                $seenOffsets[$key] = true;
            }
        }

        return [
            'errors' => [
                'error' => $uniqueDetails ? implode("\n", array_column($uniqueDetails, 'message')) : null,
                'correction' => $correction,
                'details' => $uniqueDetails
            ]
        ];
    }

    private function getErrorContext(string $text, int $offset, int $length): string
{
    $start = max(0, $offset - 20);
    $end = min(mb_strlen($text), $offset + $length + 20);
    $context = mb_substr($text, $start, $end - $start);

    if ($start > 0) $context = '...'.$context;
    if ($end < mb_strlen($text)) $context = $context.'...';

    $errorStart = $offset - $start;
    $errorEnd = $errorStart + $length;
    $before = mb_substr($context, 0, $errorStart);
    $error = mb_substr($context, $errorStart, $length);
    $after = mb_substr($context, $errorEnd);

    return $before.'<mark>'.$error.'</mark>'.$after;
}
}
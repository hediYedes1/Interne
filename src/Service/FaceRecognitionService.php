<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;

class FaceRecognitionService
{
    private $apiKey;
    private $apiSecret;
    private $httpClient;
    private $logger;
    private $baseUrl = 'https://api-us.faceplusplus.com/facepp/v3';

    public function __construct(
        HttpClientInterface $httpClient,
        ParameterBagInterface $params,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->apiKey = $params->get('face_api_key');
        $this->apiSecret = $params->get('face_api_secret');
        $this->logger = $logger;
    }

    public function getParameter(string $key): ?string
    {
        $parameters = [
            'face_set_token' => $_ENV['FACE_SET_TOKEN'] ?? null
        ];

        if ($parameters[$key] === null) {
            $this->logger->error('Face++ configuration missing', ['key' => $key]);
            throw new \RuntimeException("Face++ configuration missing: $key");
        }

        return $parameters[$key];
    }

    public function getFaceEmbedding(string $base64Image): ?string
    {
        try {
            $response = $this->httpClient->request('POST', $this->baseUrl.'/detect', [
                'timeout' => 30,
                'body' => [
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'image_base64' => $base64Image,
                    'return_landmark' => '1',
                ],
            ]);

            $data = $response->toArray();
            
            if (empty($data['faces'])) {
                throw new \Exception('No faces detected in image');
            }

            if (count($data['faces']) > 1) {
                throw new \Exception('Multiple faces detected');
            }

            return $data['faces'][0]['face_token'];
            
        } catch (\Exception $e) {
            $this->logger->error('Face++ Error: '.$e->getMessage());
            return null;
        }
    }

    public function searchFace(string $base64Image, string $storedFaceSetToken): array
    {
        try {
            $this->logger->info('Starting face search', ['face_set_token' => $storedFaceSetToken]);
            
            $this->logger->debug('Detecting face in image');
            $detectResponse = $this->httpClient->request('POST', $this->baseUrl.'/detect', [
                'timeout' => 30,
                'body' => [
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'image_base64' => $base64Image,
                ],
            ]);

            $detectData = $detectResponse->toArray();
            $this->logger->debug('Face detection results', $detectData);

            if (empty($detectData['faces'])) {
                $this->logger->error('No faces detected in image');
                return [];
            }

            $faceToken = $detectData['faces'][0]['face_token'];
            $this->logger->debug('Face token extracted', ['token' => $faceToken]);

            $searchResponse = $this->httpClient->request('POST', $this->baseUrl.'/search', [
                'timeout' => 30,
                'body' => [
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'face_token' => $faceToken,
                    'faceset_token' => $storedFaceSetToken,
                    'return_result_count' => 1,
                ],
            ]);

            $searchData = $searchResponse->toArray();
            $this->logger->debug('Face++ Search Response', $searchData);

            if (isset($searchData['error_message'])) {
                $this->logger->error('Face++ API Error', ['error' => $searchData['error_message']]);
                return [];
            }

            return $searchData['results'] ?? [];
        } catch (\Exception $e) {
            $this->logger->error('Face++ Search Error', [
                'message' => $e->getMessage(),
                'face_set_token' => $storedFaceSetToken
            ]);
            return [];
        }
    }

    public function detectFaces(string $base64Image): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->baseUrl . '/detect', [
                'body' => [
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'image_base64' => $base64Image,
                ],
            ]);

            $data = $response->toArray();
            return $data['faces'] ?? [];
        } catch (\Exception $e) {
            $this->logger->error('Face++ API error: ' . $e->getMessage());
            return [];
        }
    }

    public function createFaceSet(string $displayName = 'UserFaces'): ?string
    {
        try {
            $response = $this->httpClient->request('POST', $this->baseUrl.'/faceset/create', [
                'body' => [
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'display_name' => $displayName,
                ],
            ]);

            $data = $response->toArray();
            return $data['faceset_token'] ?? null;
        } catch (\Exception $e) {
            $this->logger->error('Face++ Create FaceSet Error: '.$e->getMessage());
            return null;
        }
    }

    public function addFaceToSet(string $faceToken, string $faceSetToken): bool
    {
        try {
            $this->logger->info('Adding face to set', ['face_token' => $faceToken, 'face_set_token' => $faceSetToken]);
            
            $response = $this->httpClient->request('POST', $this->baseUrl.'/faceset/addface', [
                'timeout' => 30,
                'body' => [
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'faceset_token' => $faceSetToken,
                    'face_tokens' => $faceToken,
                ],
            ]);

            $data = $response->toArray();
            $this->logger->debug('Face++ Add Face Response', $data);

            if (isset($data['error_message'])) {
                $this->logger->error('Face++ API Error', ['error' => $data['error_message']]);
                return false;
            }

            return isset($data['faceset_token']);
        } catch (\Exception $e) {
            $this->logger->error('Face++ Add Face Error', [
                'message' => $e->getMessage(),
                'face_token' => $faceToken,
                'face_set_token' => $faceSetToken
            ]);
            return false;
        }
    }
}

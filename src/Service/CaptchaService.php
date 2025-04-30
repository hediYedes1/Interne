<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CaptchaService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $apiHost;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = 'a9ce27bfebmshf2f9cbf3690c773p136bafjsnb01c39ea0d9c';
        $this->apiHost = 'captcha-generator.p.rapidapi.com';
    }

    public function generateCaptcha(): array
    {
        $response = $this->httpClient->request('GET', 'https://captcha-generator.p.rapidapi.com/', [
            'query' => [
                'noise_number' => 10,
                'fontname' => 'sora'
            ],
            'headers' => [
                'x-rapidapi-host' => $this->apiHost,
                'x-rapidapi-key' => $this->apiKey
            ]
        ]);

        return json_decode($response->getContent(), true);
    }

    public function verifyCaptcha(?string $userInput, ?string $solution): bool
    {
        if ($userInput === null || $solution === null) {
            return false;
        }
        
        return strtoupper(trim($userInput)) === strtoupper(trim($solution));
    }
} 
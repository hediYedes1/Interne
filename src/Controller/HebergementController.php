<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Form\HebergementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/hebergement')]
final class HebergementController extends AbstractController
{
    #[Route(name: 'app_hebergement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, HttpClientInterface $client): Response
    {
        $hebergements = $entityManager
            ->getRepository(Hebergement::class)
            ->findAll();

        // Fetch weather data
        $weather = null;
        try {
            $city = 'Tunis'; 
            $url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/" .
                   urlencode($city) . "?unitGroup=us&key=EJ78WFWNHH9DMBS7CUK53WYKK&contentType=json";

            $response = $client->request('GET', $url);
            $data = $response->toArray();

            $description = $data['description'] ?? 'Pas de description';
            $tempF = $data['days'][0]['temp'] ?? null;

            if ($tempF !== null) {
                $tempC = ($tempF - 32) * 5 / 9;
                $weather = [
                    'tempC' => round($tempC, 1),
                    'description' => $description,
                ];
            }
        } catch (\Exception $e) {
            $weather = null;
        }
        
        // Fetch currency conversion rates
        $currencyRates = [
            'USD' => null,
            'EUR' => null
        ];

        try {
            // Get USD rate for 1 TND
            $apiKey = 'fe64f2ddc902ece774ba254114691ddc';
            $usdUrl = "https://api.exchangerate.host/convert?access_key={$apiKey}&from=TND&to=USD&amount=1";
            $eurUrl = "https://api.exchangerate.host/convert?access_key={$apiKey}&from=TND&to=EUR&amount=1";
            
            // Make USD request
            $usdResponse = $client->request('GET', $usdUrl);
            $usdData = $usdResponse->toArray();
            
            // Log the response for debugging
            error_log('USD API Response: ' . json_encode($usdData));
            
            // Make EUR request
            $eurResponse = $client->request('GET', $eurUrl);
            $eurData = $eurResponse->toArray();
            
            // Log the response for debugging
            error_log('EUR API Response: ' . json_encode($eurData));
            
            // Check if the API calls were successful
            if (isset($usdData['success']) && $usdData['success'] === true && isset($usdData['result'])) {
                $currencyRates['USD'] = $usdData['result'];
            } else {
                error_log('USD API call failed: ' . json_encode($usdData));
            }
            
            if (isset($eurData['success']) && $eurData['success'] === true && isset($eurData['result'])) {
                $currencyRates['EUR'] = $eurData['result'];
            } else {
                error_log('EUR API call failed: ' . json_encode($eurData));
            }
        } catch (\Exception $e) {
            // Log the exception
            error_log('Currency API Exception: ' . $e->getMessage());
        }
        
        // If the primary API fails, try a fallback API
        if ($currencyRates['USD'] === null || $currencyRates['EUR'] === null) {
            try {
                // Fallback to a different API
                $fallbackUrl = "https://open.er-api.com/v6/latest/TND";
                $fallbackResponse = $client->request('GET', $fallbackUrl);
                $fallbackData = $fallbackResponse->toArray();
                
                // Log the fallback response
                error_log('Fallback API Response: ' . json_encode($fallbackData));
                
                if (isset($fallbackData['rates'])) {
                    if ($currencyRates['USD'] === null && isset($fallbackData['rates']['USD'])) {
                        $currencyRates['USD'] = $fallbackData['rates']['USD'];
                    }
                    
                    if ($currencyRates['EUR'] === null && isset($fallbackData['rates']['EUR'])) {
                        $currencyRates['EUR'] = $fallbackData['rates']['EUR'];
                    }
                }
            } catch (\Exception $e) {
                error_log('Fallback API Exception: ' . $e->getMessage());
            }
        }

        // If all APIs fail, use hardcoded approximate values as a last resort
        if ($currencyRates['USD'] === null) {
            $currencyRates['USD'] = 0.33; // Approximate TND to USD rate
            error_log('Using hardcoded USD rate');
        }

        if ($currencyRates['EUR'] === null) {
            $currencyRates['EUR'] = 0.30; // Approximate TND to EUR rate
            error_log('Using hardcoded EUR rate');
        }
        
        // Calculate converted prices for each hebergement
        foreach ($hebergements as $hebergement) {
            $price = $hebergement->getPrixhebergement();
            $convertedPrices = [
                'USD' => $currencyRates['USD'] ? round($price * $currencyRates['USD'], 2) : null,
                'EUR' => $currencyRates['EUR'] ? round($price * $currencyRates['EUR'], 2) : null
            ];
            
            // Add converted prices to hebergement object
            $hebergement->convertedPrices = $convertedPrices;
        }

        return $this->render('hebergement/index.html.twig', [
            'hebergements' => $hebergements,
            'weather' => $weather,
            'currencyRates' => $currencyRates
        ]);
    }
    
    

    #[Route('/new', name: 'app_hebergement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hebergement);
            $entityManager->flush();

            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hebergement/new.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_hebergement_show', methods: ['GET'])]
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('hebergement/show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    #[Route('/{idhebergement}/edit', name: 'app_hebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hebergement/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_hebergement_delete', methods: ['POST'])]
    public function delete(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hebergement->getIdhebergement(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($hebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
    }
}




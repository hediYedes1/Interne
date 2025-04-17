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
    
        return $this->render('hebergement/index.html.twig', [
            'hebergements' => $hebergements,
            'weather' => $weather
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

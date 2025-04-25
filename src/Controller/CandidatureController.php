<?php
// src/Controller/CandidatureController.php

namespace App\Controller;

use App\Entity\Offre;
use App\services\CVParserService;
use App\services\SimilarityService;
use App\services\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatureController extends AbstractController
{
    #[Route('/offre/{id}/postuler', name: 'app_postuler', methods: ['GET', 'POST'])]
    public function postuler(
        Request $request,
        Offre $offre,
        UploadService $uploadService,
        CVParserService $cvParserService,
        SimilarityService $similarityService
    ): Response {
        $similarityScore = null;

        if ($request->isMethod('POST')) {
            $cvFile = $request->files->get('cv');

            if ($cvFile) {
                try {
                    // 1. Upload CV
                    $cvUrl = $uploadService->uploadFile($cvFile);

                    if (!$cvUrl) {
                        $this->addFlash('error', 'Erreur lors du téléchargement du CV');
                    } else {
                        // 2. Parse CV text
                        $cvText = $cvParserService->extractText($cvFile);

                        // 3. Calculate similarity
                        $similarityScore = $similarityService->calculateSimilarity(
                            $cvText,
                            $offre->getDescriptionoffre()
                        );

                        $this->addFlash('info', sprintf('Score de similarité : %.2f%%', $similarityScore));
                    }
                } catch (\RuntimeException $e) {
                    $this->addFlash('error', $e->getMessage());
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur inattendue est survenue lors de la candidature.');
                }
            } else {
                $this->addFlash('warning', 'Veuillez sélectionner un fichier CV.');
            }
        }

        return $this->render('candidature/postuler.html.twig', [
            'offre' => $offre,
            'similarity_score' => $similarityScore
        ]);
    }
}

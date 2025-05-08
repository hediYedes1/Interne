<?php
// src/Controller/CandidatureController.php

namespace App\Controller;

use App\Entity\Offre;
use App\services\CVParserService;
use App\services\SimilarityService;
use App\services\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Affectationinterview;
use App\Entity\Interview;
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
        SimilarityService $similarityService,
        EntityManagerInterface $em
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
                        if ($similarityScore > 50) {
                            $this->addFlash('success', 'Score supérieur à 50%, entretien affecté automatiquement.');
                        
                            // Récupérer l'utilisateur connecté
                            /** @var Utilisateur $user */
                            $user = $this->getUser();
                        
                            // Créer une nouvelle affectation d'entretien
                            $affectation = new Affectationinterview();
                        
                            // Pour l'exemple : créer une nouvelle Interview à la volée (à adapter selon ton métier)
                            $interview = new Interview();
                            $interview->setDateinterview(new \DateTime('+3 days')); // Exemple : 3 jours plus tard
                        
                            // Persister les entités
                            $em->persist($interview);
                            $affectation->setIdinterview($interview);
                            $affectation->setIdutilisateur($user);
                            $em->persist($affectation);
                            $em->flush();
                        } else {
                            $this->addFlash('warning', 'Score inférieur à 50%, aucune interview affectée.');
                        }
                        
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

<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class FaceIdService
{
    private $faceRecognitionService;
    private $entityManager;
    private $logger;
    private $similarityThreshold = 70.0;

    public function __construct(
        FaceRecognitionService $faceRecognitionService, 
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->faceRecognitionService = $faceRecognitionService;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }
    
    public function verifyUserFace(string $base64Image, int $userId): ?bool
    {
        try {
            // Get user from database
            $user = $this->entityManager->getRepository(Utilisateur::class)->find($userId);
            if (!$user) {
                $this->logger->error('User not found', ['userId' => $userId]);
                return null;
            }

            // Check if user has a face embedding stored
            $storedEmbedding = $user->getFaceEmbedding();
            if (!$storedEmbedding) {
                $this->logger->info('User does not have a face embedding stored', ['userId' => $userId]);
                return false;
            }
            
            // Get the embedding for the uploaded image
            $newEmbedding = $this->faceRecognitionService->getFaceEmbedding($base64Image);
            if (!$newEmbedding) {
                $this->logger->error('Failed to get face embedding from uploaded image');
                return null;
            }
            
            // Compare the embeddings
            $similarity = $this->faceRecognitionService->compareFaces($newEmbedding, $storedEmbedding);
            $this->logger->info('Face comparison result', ['similarity' => $similarity]);
            
            return $similarity >= $this->similarityThreshold;
        } catch (\Exception $e) {
            $this->logger->error('Error during face verification', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function registerUserFace(string $base64Image, int $userId): bool
    {
        try {
            // Get user from database
            $user = $this->entityManager->getRepository(Utilisateur::class)->find($userId);
            if (!$user) {
                $this->logger->error('User not found', ['userId' => $userId]);
                return false;
            }
            
            // Get the embedding for the uploaded image
            $embedding = $this->faceRecognitionService->getFaceEmbedding($base64Image);
            if (!$embedding) {
                $this->logger->error('Failed to get face embedding from uploaded image');
                return false;
            }
            
            // Store the embedding in the user record
            $user->setFaceEmbedding($embedding);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            
            $this->logger->info('Successfully registered face for user', ['userId' => $userId]);
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Error during face registration', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    public function updateUserFace(string $base64Image, int $userId): bool
    {
        return $this->registerUserFace($base64Image, $userId);
    }
    
    public function deleteUserFace(int $userId): bool
    {
        try {
            // Get user from database
            $user = $this->entityManager->getRepository(Utilisateur::class)->find($userId);
            if (!$user) {
                $this->logger->error('User not found', ['userId' => $userId]);
                return false;
            }
            
            // Delete the face embedding
            $user->setFaceEmbedding(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            
            $this->logger->info('Successfully deleted face for user', ['userId' => $userId]);
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Error during face deletion', ['error' => $e->getMessage()]);
            return false;
        }
    }
}

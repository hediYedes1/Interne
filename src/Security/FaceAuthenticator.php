<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Service\FaceRecognitionService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FaceAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        #[Autowire(service: 'App\Service\FaceRecognitionService')]
        private FaceRecognitionService $faceService,
        #[Autowire(service: 'doctrine.orm.entity_manager')]
        private EntityManagerInterface $entityManager,
        #[Autowire(service: 'logger')]
        private LoggerInterface $logger
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_face_login' 
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $this->logger->info('Starting face authentication');

        $faceImage = $request->files->get('face_image');
        if (!$faceImage) {
            $this->logger->error('No face image provided');
            throw new CustomUserMessageAuthenticationException('No face image provided');
        }

        // Validate image type
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($faceImage->getMimeType(), $allowedTypes)) {
            $this->logger->error('Invalid image type', ['type' => $faceImage->getMimeType()]);
            throw new CustomUserMessageAuthenticationException('Invalid image format');
        }

        try {
            $imageData = file_get_contents($faceImage->getPathname());
            $base64Image = base64_encode($imageData);
            
            $faceSetToken = $this->faceService->getParameter('face_set_token');
            $results = $this->faceService->searchFace($base64Image, $faceSetToken);

            if (empty($results)) {
                $this->logger->info('No matching face found');
                throw new CustomUserMessageAuthenticationException('Face not recognized');
            }

            $bestMatch = $results[0];
            if ($bestMatch['confidence'] < 80) {
                $this->logger->info('Low confidence match', ['confidence' => $bestMatch['confidence']]);
                throw new CustomUserMessageAuthenticationException('Face match confidence too low');
            }

            $user = $this->entityManager->getRepository(Utilisateur::class)
                ->findOneBy(['faceEmbedding' => $bestMatch['face_token']]);

            if (!$user) {
                $this->logger->error('User not found for face token', ['token' => $bestMatch['face_token']]);
                throw new CustomUserMessageAuthenticationException('User account not found');
            }

            $this->logger->info('Face authentication successful', ['user_id' => $user->getId()]);

            return new SelfValidatingPassport(
                new UserBadge($user->getEmailutilisateur(), function() use ($user) {
                    return $user;
                })
            );

        } catch (\Exception $e) {
            $this->logger->error('Face authentication failed', ['error' => $e->getMessage()]);
            throw new CustomUserMessageAuthenticationException('Authentication failed');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null; // Let the controller handle the response
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'success' => false,
            'error' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }
}
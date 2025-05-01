<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\Role;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\FaceRecognitionService; 
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; 
use Psr\Log\LoggerInterface;
use App\Service\CaptchaService;

class SecurityController extends AbstractController
{
    private CaptchaService $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_base3');
        }

        // Generate CAPTCHA
        $captcha = $this->captchaService->generateCaptcha();

        // Handle AJAX request for credential validation
        if ($request->isXmlHttpRequest()) {
            $email = $request->request->get('_email');
            $password = $request->request->get('_password');

            if (empty($email) || empty($password)) {
                return new JsonResponse(['message' => 'Veuillez remplir tous les champs requis'], Response::HTTP_BAD_REQUEST);
            }

            // Here you would typically validate the credentials against your user provider
            // For now, we'll just return success to show the CAPTCHA
            return new JsonResponse(['success' => true]);
        }

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('utilisateur/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'captcha' => $captcha
        ]);
    }

    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $user->setNomutilisateur($request->request->get('nom'));
            $user->setPrenomutilisateur($request->request->get('prenom'));
            $user->setAgeutilisateur((int) $request->request->get('age'));
            $user->setEmailutilisateur($request->request->get('email'));
            $user->setMotdepasseutilisateur($passwordHasher->hashPassword($user, $request->request->get('password')));
            $user->setRole(Role::CANDIDAT); // Assuming 'CANDIDAT' is a valid role in your system
            $user->setResettoken('');
            $user->setProfilepictureurl('default.jpg');

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('utilisateur/signUp.html.twig');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/face-login', name: 'app_face_login', methods: ['GET', 'POST'])]
    public function faceLogin(
        Request $request,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        FaceRecognitionService $faceService,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            try {
                $logger->info('Face login attempt started');
                
                $faceImage = $request->files->get('face_image');
                if (!$faceImage) {
                    $logger->error('No face image provided');
                    return new JsonResponse(['success' => false, 'error' => 'No face image provided']);
                }

                // Validate image
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array($faceImage->getMimeType(), $allowedTypes)) {
                    $logger->error('Invalid image type: '.$faceImage->getMimeType());
                    return new JsonResponse(['success' => false, 'error' => 'Invalid image format']);
                }

                // Check FaceSet
                $faceSetToken = $faceService->getParameter('face_set_token');
                if (!$faceSetToken) {
                    $logger->critical('FaceSet token not configured');
                    return new JsonResponse(['success' => false, 'error' => 'System configuration error']);
                }

                // Process image
                $imageData = file_get_contents($faceImage->getPathname());
                $base64Image = base64_encode($imageData);
                $logger->debug('Image processed, calling Face++ API');

                // Call Face++ API
                $results = $faceService->searchFace($base64Image, $faceSetToken);
                $logger->debug('Face++ API response', ['results' => $results]);

                if (empty($results)) {
                    $logger->info('No matching face found');
                    return new JsonResponse(['success' => false, 'error' => 'Face not recognized']);
                }

                $bestMatch = $results[0];
                if ($bestMatch['confidence'] < 80) {
                    $logger->info('Low confidence match', ['confidence' => $bestMatch['confidence']]);
                    return new JsonResponse(['success' => false, 'error' => 'Face match confidence too low']);
                }

                // Find user
                $user = $entityManager->getRepository(Utilisateur::class)
                    ->findOneBy(['faceEmbedding' => $bestMatch['face_token']]);

                if (!$user) {
                    $logger->error('User not found for face token', ['token' => $bestMatch['face_token']]);
                    return new JsonResponse(['success' => false, 'error' => 'User account not found']);
                }

                // Authenticate
                $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
                $tokenStorage->setToken($token);
                $request->getSession()->set('_security_main', serialize($token));
                $logger->info('User authenticated successfully', ['user_id' => $user->getId()]);

                return new JsonResponse([
                    'success' => true,
                    'redirect' => $this->generateUrl('app_base')
                ]);

            } catch (\Exception $e) {
                $logger->error('Face login error', ['exception' => $e]);
                return new JsonResponse([
                    'success' => false,
                    'error' => 'An error occurred during face authentication'
                ]);
            }
        }

        return $this->render('security/face-login.html.twig');
    }
    
    #[Route('/register-face', name: 'app_register_face', methods: ['POST'])]
    public function registerFace(
        Request $request,
        FaceRecognitionService $faceService,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['success' => false, 'error' => 'User not authenticated']);
        }
        
        try {
            $faceImage = $request->files->get('face_image');
            if (!$faceImage) {
                return new JsonResponse(['success' => false, 'error' => 'No face image provided']);
            }
    
            $imageData = file_get_contents($faceImage->getPathname());
            $base64Image = base64_encode($imageData);
    
            // Get face token
            $faceToken = $faceService->getFaceEmbedding($base64Image);
            if (!$faceToken) {
                return new JsonResponse(['success' => false, 'error' => 'Could not detect face']);
            }
    
            // Add to face set
            $faceSetToken = $faceService->getParameter('face_set_token');
            $success = $faceService->addFaceToSet($faceToken, $faceSetToken);
    
            if ($success) {
                // Update user
                $user->setFaceEmbedding($faceToken);
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse(['success' => true, 'message' => 'Face registered successfully']);
            }
    
            return new JsonResponse(['success' => false, 'error' => 'Failed to register face']);
        } catch (\Exception $e) {
            error_log('Face registration error: ' . $e->getMessage());
            return new JsonResponse(['success' => false, 'error' => 'Error registering face']);
        }
    }
}
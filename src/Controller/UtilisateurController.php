<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\FaceRecognitionService;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{
    #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search', ''); 
        $field = $request->query->get('field', 'nomutilisateur'); 
        $sort = $request->query->get('sort', 'nomutilisateur'); 
        $direction = $request->query->get('direction', 'asc'); 

        $allowedFields = ['nomutilisateur', 'prenomutilisateur', 'emailutilisateur', 'ageutilisateur'];
        if (!in_array($field, $allowedFields)) {
            $field = 'nomutilisateur';
        }

        $queryBuilder = $entityManager->getRepository(Utilisateur::class)->createQueryBuilder('u');

        if (!empty($search)) {
            $queryBuilder->where("u.$field LIKE :search")
                ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('u.' . $sort, $direction);

        $utilisateurs = $queryBuilder->getQuery()->getResult();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'search' => $search,
            'field' => $field,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{idutilisateur}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{idutilisateur}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idutilisateur}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getIdutilisateur(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/utilisateur/register-face', name: 'app_utilisateur_register_face', methods: ['POST'])]
    public function registerFace(
        Request $request,
        EntityManagerInterface $entityManager,
        FaceRecognitionService $faceService
    ): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user instanceof Utilisateur) {
                return new JsonResponse(['success' => false, 'error' => 'User not authenticated or invalid user type']);
            }

            $faceImage = $request->files->get('face_image');
            if (!$faceImage) {
                return new JsonResponse(['success' => false, 'error' => 'No face image provided']);
            }

            // Validate image
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($faceImage->getMimeType(), $allowedTypes)) {
                return new JsonResponse(['success' => false, 'error' => 'Invalid image format. Please use JPEG or PNG']);
            }

            // Process the image
            $imageData = file_get_contents($faceImage->getPathname());
            if ($imageData === false) {
                return new JsonResponse(['success' => false, 'error' => 'Failed to read image data']);
            }

            $base64Image = base64_encode($imageData);
            if ($base64Image === false) {
                return new JsonResponse(['success' => false, 'error' => 'Failed to encode image data']);
            }

            // Get face token from Face++ API
            $faceToken = $faceService->getFaceEmbedding($base64Image);
            if (!$faceToken) {
                return new JsonResponse(['success' => false, 'error' => 'Could not detect face']);
            }

            // Add to face set
            $faceSetToken = $faceService->getParameter('face_set_token');
            $success = $faceService->addFaceToSet($faceToken, $faceSetToken);

            if ($success) {
                // Store the face token
                $user->setFaceEmbedding($faceToken);
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse(['success' => true, 'message' => 'Face registered successfully']);
            }

            return new JsonResponse(['success' => false, 'error' => 'Failed to register face']);
        } catch (\Exception $e) {
            error_log('Face registration error: ' . $e->getMessage());
            return new JsonResponse([
                'success' => false, 
                'error' => 'Error processing face image: ' . $e->getMessage()
            ]);
        }
    }
}
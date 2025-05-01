<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Projet;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/offre')]
class OffreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OffreRepository $offreRepository
    ) {}

    #[Route('/list', name: 'front_offre_index', methods: ['GET'])]
    public function indexFront(Request $request, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('search', '');
        $typeContrat = $request->query->get('typecontrat', '');
        
        $query = $this->offreRepository->getSearchQuery($searchQuery, $typeContrat);
        $offres = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render('offre/_offres_list.html.twig', [
                'offres' => $offres,
                'searchQuery' => $searchQuery,
                'selectedType' => $typeContrat,
            ]);
        }

        return $this->render('offre/indexfront.html.twig', [
            'offres' => $offres,
            'searchQuery' => $searchQuery,
            'selectedType' => $typeContrat
        ]);
    }

    #[Route('/new', name: 'front_offre_new', methods: ['GET', 'POST'])]
    public function newFront(Request $request, Security $security): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            if ($user) {
                $offre->setIdutilisateur($user);
                if (method_exists($user, 'getEntreprise')) {
                    $offre->setIdentreprise($user->getEntreprise());
                }
            }

            $this->entityManager->persist($offre);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre offre a été créée avec succès');
            return $this->redirectToRoute('front_offre_show', ['idoffre' => $offre->getIdoffre()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Erreur lors de la création de l\'offre. Veuillez vérifier les informations.');
        }

        return $this->render('offre/newfront.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idoffre}', name: 'front_offre_show', methods: ['GET'], requirements: ['idoffre' => '\d+'])]
    public function showFront(int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);

        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée');
            return $this->redirectToRoute('front_offre_index');
        }

        $now = new \DateTime();
        $datelimite = $offre->getDatelimite();
        $remainingSeconds = $datelimite->getTimestamp() - $now->getTimestamp();
        $isExpired = $remainingSeconds <= 0;

        if ($isExpired) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('warning', 'Cette offre a expiré et a été automatiquement supprimée');
            return $this->redirectToRoute('front_offre_index');
        }

        return $this->render('offre/showfront.html.twig', [
            'offre' => $offre,
            'projet' => $offre->getProjet(),
            'isExpired' => $isExpired,
            'remainingTime' => $remainingSeconds,
            'datelimiteFormatted' => $datelimite->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/{idoffre}/expire', name: 'front_offre_expire', methods: ['POST'])]
    public function expireOffer(int $idoffre, Request $request): JsonResponse
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);

        if (!$offre) {
            return new JsonResponse(['error' => 'Offre non trouvée'], 404);
        }

        $this->entityManager->remove($offre);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{idoffre}/edit', name: 'front_offre_edit', methods: ['GET', 'POST'], requirements: ['idoffre' => '\d+'])]
    public function editFront(Request $request, int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);
        
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée');
            return $this->redirectToRoute('front_offre_index');
        }

        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'offre a été modifiée avec succès');
            return $this->redirectToRoute('front_offre_show', ['idoffre' => $offre->getIdoffre()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Erreur lors de la modification. Veuillez vérifier les informations.');
        }

        return $this->render('offre/editfront.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idoffre}/delete', name: 'front_offre_delete', methods: ['POST'], requirements: ['idoffre' => '\d+'])]
    public function deleteFront(Request $request, int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);
        
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée');
            return $this->redirectToRoute('front_offre_index');
        }

        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'offre a été supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide. La suppression a échoué.');
        }

        return $this->redirectToRoute('front_offre_index');
    }

    #[Route('/admin/offres', name: 'back_offre_index', methods: ['GET'])]
    public function indexBack(Request $request, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('search', '');
        $typeContrat = $request->query->get('typecontrat', '');
        
        $query = $this->offreRepository->getSearchQuery($searchQuery, $typeContrat);
        $offres = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            2
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render('offre/_offres_list.html.twig', [
                'offres' => $offres
            ]);
        }

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'searchQuery' => $searchQuery,
            'selectedType' => $typeContrat
        ]);
    }

    #[Route('/admin/new', name: 'back_offre_new', methods: ['GET', 'POST'])]
    public function newBack(Request $request, Security $security): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            if ($user) {
                $offre->setIdutilisateur($user);
                if (method_exists($user, 'getEntreprise')) {
                    $offre->setIdentreprise($user->getEntreprise());
                }
            }

            $this->entityManager->persist($offre);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'offre a été créée avec succès');
            return $this->redirectToRoute('back_offre_show', ['idoffre' => $offre->getIdoffre()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Erreur lors de la création de l\'offre. Veuillez vérifier les informations.');
        }

        return $this->render('offre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{idoffre}', name: 'back_offre_show', methods: ['GET'], requirements: ['idoffre' => '\d+'])]
    public function showBack(int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);
        
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée');
            return $this->redirectToRoute('back_offre_index');
        }

        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'projet' => $offre->getProjet()
        ]);
    }

    #[Route('/admin/{idoffre}/edit', name: 'back_offre_edit', methods: ['GET', 'POST'], requirements: ['idoffre' => '\d+'])]
    public function editBack(Request $request, int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);
        
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée');
            return $this->redirectToRoute('back_offre_index');
        }

        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'offre a été modifiée avec succès');
            return $this->redirectToRoute('back_offre_show', ['idoffre' => $offre->getIdoffre()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Erreur lors de la modification. Veuillez vérifier les informations.');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{idoffre}/delete', name: 'back_offre_delete', methods: ['POST'], requirements: ['idoffre' => '\d+'])]
    public function deleteBack(Request $request, int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);
        
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée');
            return $this->redirectToRoute('back_offre_index');
        }

        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'offre a été supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide. La suppression a échoué.');
        }

        return $this->redirectToRoute('back_offre_index');
    }
}
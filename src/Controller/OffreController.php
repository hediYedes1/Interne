<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Projet;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/offre')]
class OffreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/list', name: 'front_offre_index', methods: ['GET'])]
    public function indexFront(Request $request): Response
    {
        $selectedType = $request->query->get('typecontrat');
        
        $queryBuilder = $this->entityManager
            ->getRepository(Offre::class)
            ->createQueryBuilder('o');
        
        if ($selectedType && in_array($selectedType, ['CDI', 'CDD', 'STAGE'])) {
            $queryBuilder
                ->andWhere('o.typecontrat = :type')
                ->setParameter('type', $selectedType);
        }
        
        $offres = $queryBuilder->getQuery()->getResult();
        
        return $this->render('offre/indexfront.html.twig', [
            'offres' => $offres,
            'typeContratOptions' => [
                'CDI' => 'CDI',
                'CDD' => 'CDD',
                'Stage' => 'STAGE'
            ],
            'selectedType' => $selectedType
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

            $this->addFlash('success', 'Offre créée avec succès');
            return $this->redirectToRoute('front_offre_show', ['idoffre' => $offre->getIdoffre()]);
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
            throw $this->createNotFoundException('Offre non trouvée');
        }

        return $this->render('offre/showfront.html.twig', [
            'offre' => $offre,
            'projet' => $offre->getProjet()
        ]);
    }

    #[Route('/{idoffre}/edit', name: 'front_offre_edit', methods: ['GET', 'POST'], requirements: ['idoffre' => '\d+'])]
    public function editFront(Request $request, int $idoffre): Response
    {
        $offre = $this->entityManager->getRepository(Offre::class)->find($idoffre);
        
        if (!$offre) {
            throw $this->createNotFoundException('Offre non trouvée');
        }

        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre modifiée avec succès');
            return $this->redirectToRoute('front_offre_show', ['idoffre' => $offre->getIdoffre()]);
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
            throw $this->createNotFoundException('Offre non trouvée');
        }

        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès');
        }

        return $this->redirectToRoute('front_offre_index');
    }

    #[Route('/admin/offres', name: 'back_offre_index', methods: ['GET'])]
    public function indexBack(): Response
    {
        $offres = $this->entityManager->getRepository(Offre::class)->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
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

            $this->addFlash('success', 'Offre créée avec succès');
            return $this->redirectToRoute('back_offre_show', ['idoffre' => $offre->getIdoffre()]);
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
            throw $this->createNotFoundException('Offre non trouvée');
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
            throw $this->createNotFoundException('Offre non trouvée');
        }

        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre modifiée avec succès');
            return $this->redirectToRoute('back_offre_show', ['idoffre' => $offre->getIdoffre()]);
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
            throw $this->createNotFoundException('Offre non trouvée');
        }

        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès');
        }

        return $this->redirectToRoute('back_offre_index');
    }
}
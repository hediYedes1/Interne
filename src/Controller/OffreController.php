<?php
namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Projet;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    // Route pour la partie front (candidats) - Liste des offres
    #[Route('/list', name: 'front_offre_index', methods: ['GET'])]
    public function indexFront(): Response
    {
        $offres = $this->entityManager
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offre/indexfront.html.twig', [
            'offres' => $offres,
        ]);
    }

    // Route pour afficher une offre spécifique (front)
    #[Route('/{idoffre}', name: 'front_offre_show', methods: ['GET'])]
    public function showFront(Offre $offre): Response
    {
        // Vérifie si l'offre existe
        if (!$offre) {
            throw $this->createNotFoundException('L\'offre demandée n\'a pas été trouvée');
        }

        return $this->render('offre/showfront.html.twig', [
            'offre' => $offre,
            'projet' => $offre->getProjet()
        ]);
    }

    // Route pour créer une nouvelle offre (front)
    #[Route('/offre/new', name: 'front_offre_new', methods: ['GET', 'POST'])]
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
                // Si vous avez une relation entre User et Entreprise
                if (method_exists($user, 'getEntreprise')) {
                    $offre->setIdentreprise($user->getEntreprise());
                }
            }

            $this->entityManager->persist($offre);
            $this->entityManager->flush();

            $this->addFlash('success', 'Offre créée avec succès');
            return $this->redirectToRoute('front_offre_index');
        }

        return $this->render('offre/newfront.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour éditer une offre (front)
    #[Route('/{idoffre}/edit', name: 'front_offre_edit', methods: ['GET', 'POST'])]
    public function editFront(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre modifiée avec succès');
            return $this->redirectToRoute('front_offre_index');
        }

        return $this->render('offre/editfront.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    // Route pour supprimer une offre (front)
    #[Route('/{idoffre}/delete', name: 'front_offre_delete', methods: ['POST'])]
    public function deleteFront(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès');
        }

        return $this->redirectToRoute('front_offre_index');
    }

    // Route pour la partie back (admin/RH)
    #[Route('/admin/offres', name: 'back_offre_index', methods: ['GET'])]
    public function indexBack(): Response
    {
        $offres = $this->entityManager->getRepository(Offre::class)->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    // Route pour créer une nouvelle offre (back)
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
            return $this->redirectToRoute('back_offre_index');
        }

        return $this->render('offre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher une offre (back)
    #[Route('/admin/{idoffre}', name: 'back_offre_show', methods: ['GET'])]
    public function showBack(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'projet' => $offre->getProjet()
        ]);
    }

    // Route pour éditer une offre (back)
    #[Route('/admin/{idoffre}/edit', name: 'back_offre_edit', methods: ['GET', 'POST'])]
    public function editBack(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre, [
            'projets' => $this->entityManager->getRepository(Projet::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre modifiée avec succès');
            return $this->redirectToRoute('back_offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    // Route pour supprimer une offre (back)
    #[Route('/admin/{idoffre}', name: 'back_offre_delete', methods: ['POST'])]
    public function deleteBack(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdoffre(), $request->request->get('_token'))) {
            $this->entityManager->remove($offre);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès');
        }

        return $this->redirectToRoute('back_offre_index');
    }
}

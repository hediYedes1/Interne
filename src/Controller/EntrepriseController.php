<?php

namespace App\Controller;
use App\Repository\BrancheentrepriseRepository;
use App\Entity\Entreprise;
use App\Entity\Branche;
use App\Entity\Departmententreprise;
use App\Entity\Brancheentreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entreprise')]
final class EntrepriseController extends AbstractController
{
    // Méthode pour afficher la liste des entreprises pour le front-end
    #[Route('/front', name: 'app_entreprise_index_front', methods: ['GET'])]
    public function indexFront(EntityManagerInterface $entityManager): Response
    {
        $entreprises = $entityManager
            ->getRepository(Entreprise::class)
            ->findAll();

        return $this->render('entreprise/indexFront.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    // Méthode pour afficher les détails d'une entreprise pour le front-end
    #[Route('/front/{identreprise}', name: 'app_entreprise_show_front', methods: ['GET'])]
    public function showFront(Entreprise $entreprise): Response
    {
        // Assurez-vous que les branches sont uniques
        $branches = $entreprise->getBrancheentreprises()->toArray();
        $branches = array_unique($branches, SORT_REGULAR);
    
        return $this->render('entreprise/showFront.html.twig', [
            'entreprise' => $entreprise,
            'branches' => $branches, // Branches uniques
            'departments' => $entreprise->getDepartmententreprises(),
        ]);
    }
  
    #[Route('/rh', name: 'app_branches_back', methods: ['GET'])]
    public function indexBack(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    
        // Récupérer uniquement les branches associées à l'utilisateur
        $branches = $entityManager->getRepository(Brancheentreprise::class)->findBy(['idutilisateur' => $user]);
    
        return $this->render('entreprise/indexBack.html.twig', [
            'branches' => $branches,
        ]);
    }
    // Méthode pour afficher la liste des entreprises dans l'administration
    #[Route('/list', name: 'app_entreprise_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $entreprises = $entityManager
            ->getRepository(Entreprise::class)
            ->findAll();

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    // Méthode pour créer une nouvelle entreprise
    #[Route('/new', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logoentreprise')->getData();

            if ($file) {
                // Générer un nom de fichier unique
                $fileName = uniqid() . '.' . $file->guessExtension();

                // Déplacer le fichier vers le dossier de destination
                $file->move(
                    $this->getParameter('logo_directory'), // Défini dans config/services.yaml
                    $fileName
                );

                // Mettre à jour le champ de l'entité
                $entreprise->setLogoentreprise($fileName);
            } else {
                // Si le champ n'est pas nullable, définir un logo par défaut
                $entreprise->setLogoentreprise('default-logo.png');
            }

            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }


    // Méthode pour modifier les informations d'une entreprise
    #[Route('/{identreprise}/edit', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logoentreprise')->getData();

            if ($file) {
                // Générer un nom de fichier unique
                $fileName = uniqid() . '.' . $file->guessExtension();

                // Déplacer le fichier vers le dossier de destination
                $file->move(
                    $this->getParameter('logo_directory'),
                    $fileName
                );

                // Mettre à jour le champ de l'entité
                $entreprise->setLogoentreprise($fileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    // Méthode pour supprimer une entreprise
    #[Route('/{identreprise}', name: 'app_entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getIdentreprise(), $request->get('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
    }


    // Méthode pour afficher les détails d'une entreprise pour le front-end
    #[Route('/back/{identreprise}', name: 'app_entreprise_show_back', methods: ['GET'])]
    public function showBack(Entreprise $entreprise): Response
    {
        // Assurez-vous que les branches sont uniques
        $branches = $entreprise->getBrancheentreprises()->toArray();
        $branches = array_unique($branches, SORT_REGULAR);
    
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'branches' => $branches, // Branches uniques
            'departments' => $entreprise->getDepartmententreprises(),
        ]);
    }
}
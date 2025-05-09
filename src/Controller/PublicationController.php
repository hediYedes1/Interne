<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;

#[Route('/publication')]
final class PublicationController extends AbstractController
{
    #[Route(name: 'app_publication_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search');
        $sort = $request->query->get('sort', 'titre'); // Adjust to actual field
        $order = $request->query->get('order', 'asc');
    
        $qb = $entityManager->getRepository(Publication::class)->createQueryBuilder('p');
    
        if ($search) {
            $qb->andWhere('p.titre LIKE :search OR p.titre LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
    
        if (in_array($sort, ['titre', 'datePublication'])) {
            $qb->orderBy('p.' . $sort, strtoupper($order) === 'DESC' ? 'DESC' : 'ASC');
        }
    
        $publications = $qb->getQuery()->getResult();
    
        return $this->render('publication/index.html.twig', [
            'publications' => $publications,
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
        ]);
        
    }
    

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            
            $user = $this->getUser();
            if ($user instanceof Utilisateur) {
                $publication->setUtilisateur($user);
            }

        
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Make the filename safe
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                    $this->addFlash('error', 'Un problème est survenu lors du téléchargement de l\'image');
                }

                // Update the 'imagePath' property to store the image file name
                $publication->setImagePath($newFilename);
            }

            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{idPublication}', name: 'app_publication_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        // Nouveau commentaire lié à cette publication
        $commentaire = new Commentaire();
        $commentaire->setIdPublication($publication);
        $commentaire->setDateCommentaire(new \DateTime());

        // Formulaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');

            return $this->redirectToRoute('app_publication_show', [
                'idPublication' => $publication->getIdPublication(),
            ]);
        }

        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'form' => $form,
            'commentaires' => $entityManager->getRepository(Commentaire::class)->findBy(['idPublication' => $publication->getIdPublication()]),
        ]);
    }


    #[Route('/{idPublication}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{idPublication}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getIdPublication(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}

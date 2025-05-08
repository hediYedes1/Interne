<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Reponse;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PublicationRepository;
use App\Repository\CommentaireRepository;

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
    #[Route('/front', name: 'app_publication_index_front', methods: ['GET'])]
public function indexFront(Request $request, EntityManagerInterface $entityManager): Response
{
    $currentPage = $request->query->getInt('page', 1);
    $limit = 4;

    // Fetch publications for the current page
    $publications = $entityManager
        ->getRepository(Publication::class)
        ->findBy([], null, $limit, ($currentPage - 1) * $limit);

    // Get total publications count
    $totalPublications = $entityManager->getRepository(Publication::class)->count([]);

    // Calculate total number of pages
    $totalPages = ceil($totalPublications / $limit);

    return $this->render('publication/indexfront.html.twig', [
        'publications' => $publications,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
    ]);
}
#[Route('/front/{id}', name: 'app_publication_show_front')]
public function showFront(
    int $id, 
    PublicationRepository $publicationRepo,  
    CommentaireRepository $commentaireRepo, // Now using the correct CommentaireRepository
    Request $request, 
    EntityManagerInterface $entityManager
): Response {
    // Fetch the publication from the database
    $publication = $publicationRepo->find($id);

    if (!$publication) {
        throw $this->createNotFoundException('Publication not found');
    }

    // Fetch all comments associated with the publication
    $commentaires = $commentaireRepo->findByPublication($publication);

    // Create a form for adding new comments
    $commentaire = new Commentaire();
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    // Handle form submission and save comment
    if ($form->isSubmitted() && $form->isValid()) {
        $commentaire->setIdPublication($publication); // Link the comment to the publication
        $commentaire->setDateCommentaire(new \DateTimeImmutable()); // Set comment date
        $entityManager->persist($commentaire);
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire ajouté avec succès!');
        return $this->redirectToRoute('app_publication_show_front', ['id' => $publication->getIdPublication()]);
    }

    // Render the template with necessary data
    return $this->render('publication/showFront.html.twig', [
        'publication' => $publication, 
        'commentaires' => $commentaires, 
        'form' => $form->createView(),
    ]);
}
#[Route('/commentaire/{idCommentaire}/delete', name: 'app_commentaire_delete', methods: ['POST'])]
public function deleteComment(
    Request $request,
    Commentaire $commentaire,
    EntityManagerInterface $entityManager
): Response {
    if ($this->isCsrfTokenValid('delete' . $commentaire->getIdCommentaire(), $request->request->get('_token'))) {

        // Remove related Reponses first
        $reponses = $entityManager->getRepository(Reponse::class)
            ->findBy(['idCommentaire' => $commentaire]);

        foreach ($reponses as $reponse) {
            $entityManager->remove($reponse);
        }

        // Then remove the Commentaire
        $entityManager->remove($commentaire);
        $entityManager->flush();
    }

    // Redirect to publication show page
    return $this->redirectToRoute('app_publication_show_front', [
        'id' => $commentaire->getIdPublication()->getIdPublication()
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
}

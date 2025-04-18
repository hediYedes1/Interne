<?php

namespace App\Controller;
use App\Entity\Entreprise;
use App\Entity\Departmententreprise;
use App\Form\DepartmententrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/departmententreprise')]
final class DepartmententrepriseController extends AbstractController{
    #[Route('/list',name: 'app_departmententreprise_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $departmententreprises = $entityManager
            ->getRepository(Departmententreprise::class)
            ->findAll();

        return $this->render('departmententreprise/index.html.twig', [
            'departmententreprises' => $departmententreprises,
        ]);
    }

    #[Route('/new/{id}', name: 'app_departmententreprise_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager): Response
    {
        $departmententreprise = new Departmententreprise();
        $form = $this->createForm(DepartmententrepriseType::class, $departmententreprise);
        $form->handleRequest($request);// On récupère l'entreprise par son identifiant
        $entreprise = $entityManager->getRepository(Entreprise::class)->find($id);
    
        if (!$entreprise) {
            throw $this->createNotFoundException('Entreprise non trouvée avec l\'ID : ' . $id);
        }
    
        if ($form->isSubmitted() && $form->isValid()) {
            $departmententreprise->setIdentreprise($entreprise);
            $entityManager->persist($departmententreprise);
            $entityManager->flush();

// ✅ Redirection vers la vue de l'entreprise avec ses branches/départements
return $this->redirectToRoute('app_entreprise_show_back', [
    'identreprise' => $entreprise->getIdentreprise(), // le paramètre attendu
]);        
        }

        return $this->render('departmententreprise/new.html.twig', [
            'departmententreprise' => $departmententreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{iddepartement}', name: 'app_departmententreprise_show', methods: ['GET'])]
    public function show(Departmententreprise $departmententreprise): Response
    {
        return $this->render('departmententreprise/show.html.twig', [
            'departmententreprise' => $departmententreprise,
        ]);
    }

    #[Route('/{iddepartement}/edit', name: 'app_departmententreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departmententreprise $departmententreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartmententrepriseType::class, $departmententreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $entrepriseId = $departmententreprise->getIdentreprise()->getIdentreprise();
    
            return $this->redirectToRoute('app_entreprise_show_back', [
                'identreprise' => $entrepriseId
            ], Response::HTTP_SEE_OTHER);
                }

        return $this->render('departmententreprise/edit.html.twig', [
            'departmententreprise' => $departmententreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{iddepartement}', name: 'app_departmententreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Departmententreprise $departmententreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departmententreprise->getIddepartement(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($departmententreprise);
            $entityManager->flush();
        }

        $entrepriseId = $departmententreprise->getIdentreprise()->getIdentreprise();

        return $this->redirectToRoute('app_entreprise_show_back', [
            'identreprise' => $entrepriseId
        ], Response::HTTP_SEE_OTHER);
        }
}

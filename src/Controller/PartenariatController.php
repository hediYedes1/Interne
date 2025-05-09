<?php

namespace App\Controller;

use App\Entity\Partenariat;
use App\Form\PartenariatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/partenariat')]
final class PartenariatController extends AbstractController
{
    #[Route(name: 'app_partenariat_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $partenariats = $entityManager
            ->getRepository(Partenariat::class)
            ->findAll();

        return $this->render('partenariat/index.html.twig', [
            'partenariats' => $partenariats,
        ]);
    }
   

    #[Route('/new', name: 'app_partenariat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partenariat = new Partenariat();
        $form = $this->createForm(PartenariatType::class, $partenariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partenariat);
            $entityManager->flush();

            return $this->redirectToRoute('app_partenariat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partenariat/new.html.twig', [
            'partenariat' => $partenariat,
            'form' => $form,
        ]);
    }

    #[Route('/{idpartenariat}', name: 'app_partenariat_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, $idpartenariat): Response
    {
        $partenariat = $entityManager->getRepository(Partenariat::class)->find($idpartenariat);
        
        if (!$partenariat) {
            throw $this->createNotFoundException('Le partenariat avec l\'ID ' . $idpartenariat . ' n\'existe pas.');
        }
        
        return $this->render('partenariat/show.html.twig', [
            'partenariat' => $partenariat,
        ]);
    }

    #[Route('/{idpartenariat}/edit', name: 'app_partenariat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, $idpartenariat): Response
    {
        $partenariat = $entityManager->getRepository(Partenariat::class)->find($idpartenariat);
        
        if (!$partenariat) {
            throw $this->createNotFoundException('Le partenariat avec l\'ID ' . $idpartenariat . ' n\'existe pas.');
        }
        
        $form = $this->createForm(PartenariatType::class, $partenariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_partenariat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partenariat/edit.html.twig', [
            'partenariat' => $partenariat,
            'form' => $form,
        ]);
    }

    #[Route('/{idpartenariat}', name: 'app_partenariat_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, $idpartenariat): Response
    {
        $partenariat = $entityManager->getRepository(Partenariat::class)->find($idpartenariat);
        
        if (!$partenariat) {
            throw $this->createNotFoundException('Le partenariat avec l\'ID ' . $idpartenariat . ' n\'existe pas.');
        }
        
        if ($this->isCsrfTokenValid('delete'.$partenariat->getIdpartenariat(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($partenariat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_partenariat_index', [], Response::HTTP_SEE_OTHER);
    }
}

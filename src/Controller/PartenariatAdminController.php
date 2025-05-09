<?php

namespace App\Controller;

use App\Entity\Partenariat;
use App\Form\Partenariat2Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/partenariatadmin')]
final class PartenariatAdminController extends AbstractController
{
    #[Route('/', name: 'app_partenariat_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $partenariats = $entityManager
            ->getRepository(Partenariat::class)
            ->findAll();

        return $this->render('partenariat_admin/index.html.twig', [
            'partenariats' => $partenariats,
        ]);
    }

    #[Route('/new', name: 'app_partenariat_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partenariat = new Partenariat();
        $form = $this->createForm(Partenariat2Type::class, $partenariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partenariat);
            $entityManager->flush();

            return $this->redirectToRoute('app_partenariat_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partenariat_admin/new.html.twig', [
            'partenariat' => $partenariat,
            'form' => $form,
        ]);
    }

    #[Route('/{idpartenariat}', name: 'app_partenariat_admin_show', methods: ['GET'])]
    public function show(Partenariat $partenariat): Response
    {
        return $this->render('partenariat_admin/show.html.twig', [
            'partenariat' => $partenariat,
        ]);
    }

    #[Route('/{idpartenariat}/edit', name: 'app_partenariat_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partenariat $partenariat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Partenariat2Type::class, $partenariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_partenariat_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partenariat_admin/edit.html.twig', [
            'partenariat' => $partenariat,
            'form' => $form,
        ]);
    }

    #[Route('/{idpartenariat}', name: 'app_partenariat_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Partenariat $partenariat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenariat->getIdpartenariat(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($partenariat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_partenariat_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}

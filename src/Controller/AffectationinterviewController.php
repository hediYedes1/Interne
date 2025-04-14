<?php

namespace App\Controller;

use App\Entity\Affectationinterview;
use App\Form\AffectationinterviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Interview;
use App\Utils\EmailService;
use Psr\Log\LoggerInterface;


#[Route('/affectationinterview')]
final class AffectationinterviewController extends AbstractController
{
    #[Route('/list', name: 'app_affectationinterview_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $affectationinterviews = $entityManager
            ->getRepository(Affectationinterview::class)
            ->findAll();

        return $this->render('affectationinterview/index.html.twig', [
            'affectationinterviews' => $affectationinterviews,
        ]);
    }
/*
    #[Route('/new', name: 'app_affectationinterview_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $affectationinterview = new Affectationinterview();
        $form = $this->createForm(AffectationinterviewType::class, $affectationinterview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($affectationinterview);
            $entityManager->flush();

            return $this->redirectToRoute('app_affectationinterview_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectationinterview/new.html.twig', [
            'affectationinterview' => $affectationinterview,
            'form' => $form,
        ]);
    }
*/
    #[Route('/{idinterview}', name: 'app_affectationinterview_show', methods: ['GET'])]
    public function show(Affectationinterview $affectationinterview): Response
    {
        return $this->render('affectationinterview/show.html.twig', [
            'affectationinterview' => $affectationinterview,
        ]);
    }

    #[Route('/{idinterview}/edit', name: 'app_affectationinterview_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Affectationinterview $affectationinterview, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AffectationinterviewType::class, $affectationinterview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_affectationinterview_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectationinterview/edit.html.twig', [
            'affectationinterview' => $affectationinterview,
            'form' => $form,
        ]);
    }

    #[Route('/{idinterview}', name: 'app_affectationinterview_delete', methods: ['POST'])]
    public function delete(Request $request, Affectationinterview $affectationinterview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectationinterview->getIdinterview(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($affectationinterview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_affectationinterview_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/new/{idinterview}', name: 'app_affectationinterview_new', methods: ['GET', 'POST'])]
public function newAFF(
    Request $request, 
    Interview $interview, 
    EntityManagerInterface $entityManager,
    EmailService $emailService,
    LoggerInterface $logger
): Response {
    $affectation = new Affectationinterview();
    $affectation->setIdinterview($interview);
    $affectation->setDateaffectationinterview(new \DateTime());

    $form = $this->createForm(AffectationinterviewType::class, $affectation, [
        'interview' => $interview
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $entityManager->persist($affectation);
            $entityManager->flush();

            $utilisateur = $affectation->getIdutilisateur();
            if ($utilisateur && $email = $utilisateur->getEmailutilisateur()) {
                try {
                    if ($emailService->sendInterviewAssignmentEmail($email, $interview)) {
                        $this->addFlash('success', 'Affectation créée et email envoyé avec succès');
                    } else {
                        $this->addFlash('warning', 'Affectation créée mais échec d\'envoi de l\'email');
                    }
                } catch (\Exception $e) {
                    $logger->error('Erreur lors de l\'envoi d\'email', ['exception' => $e]);
                    $this->addFlash('error', 'Erreur technique lors de l\'envoi de l\'email');
                }
            } else {
                $this->addFlash('info', 'Affectation créée (aucun email envoyé)');
            }

            return $this->redirectToRoute('app_interview_show', [
                'idinterview' => $interview->getIdinterview()
            ]);
        } catch (\Exception $e) {
            $logger->error('Erreur création affectation', ['exception' => $e]);
            $this->addFlash('error', 'Erreur lors de la création de l\'affectation');
        }
    }

    return $this->render('affectationinterview/new.html.twig', [
        'form' => $form->createView(),
        'interview' => $interview,
    ]);
}

}

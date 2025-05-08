<?php

namespace App\Controller;

use App\Entity\Affectationhebergement;
use App\Form\AffectationhebergementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/affectationhebergement')]
final class AffectationhebergementController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(name: 'app_affectationhebergement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $affectationhebergements = $entityManager
            ->getRepository(Affectationhebergement::class)
            ->findAll();

        return $this->render('affectationhebergement/index.html.twig', [
            'affectationhebergements' => $affectationhebergements,
        ]);
    }

    #[Route('/new/{idhebergement?}/{prix?}', name: 'app_affectationhebergement_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ?int $idhebergement = null,
        ?float $prix = null
    ): Response {
        // Check user authentication
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour effectuer cette action');
            return $this->redirectToRoute('app_login');
        }

        $affectationhebergement = new Affectationhebergement();
        $affectationhebergement->setIdutilisateur($user);

        // Pre-select hebergement if provided
        if ($idhebergement) {
            $hebergement = $entityManager->getRepository(\App\Entity\Hebergement::class)->find($idhebergement);
            if ($hebergement) {
                $affectationhebergement->setIdhebergement($hebergement);
                // Ensure price is set from hebergement if not provided in URL
                $prix = $prix ?? $hebergement->getPrix();
            }
        }

        $form = $this->createForm(AffectationhebergementType::class, $affectationhebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->logger->info('Form submitted', ['submitted_data' => $request->request->all()]);

            if ($form->isValid()) {
                $this->logger->info('Form is valid, processing payment');

                try {
                    $paymentUrl = $this->processPayment($prix, $user);
                    if ($paymentUrl) {
                        $this->logger->info('Payment URL generated', ['url' => $paymentUrl]);
                        
                        // Store in session before redirect
                        $request->getSession()->set('pending_affectation', [
                            'entity' => $affectationhebergement,
                            'form_data' => $request->request->all()
                        ]);

                        return $this->redirect($paymentUrl);
                    } else {
                        $this->logger->error('Payment URL generation failed');
                        $this->addFlash('error', 'Impossible de générer le lien de paiement');
                    }
                } catch (\Exception $e) {
                    $this->logger->error('Payment processing failed', ['error' => $e->getMessage()]);
                    $this->addFlash('error', 'Une erreur est survenue lors du traitement du paiement');
                }
            } else {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }
                $this->logger->error('Form validation failed', ['errors' => $errors]);
                $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire');
            }
        }

        return $this->render('affectationhebergement/new.html.twig', [
            'affectationhebergement' => $affectationhebergement,
            'form' => $form->createView(),
            'prix' => $prix
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_affectationhebergement_show', methods: ['GET'])]
    public function show(Affectationhebergement $affectationhebergement): Response
    {
        return $this->render('affectationhebergement/show.html.twig', [
            'affectationhebergement' => $affectationhebergement,
        ]);
    }

    #[Route('/{idhebergement}/edit', name: 'app_affectationhebergement_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Affectationhebergement $affectationhebergement,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AffectationhebergementType::class, $affectationhebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Modification enregistrée avec succès');
            return $this->redirectToRoute('app_affectationhebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectationhebergement/edit.html.twig', [
            'affectationhebergement' => $affectationhebergement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idhebergement}', name: 'app_affectationhebergement_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Affectationhebergement $affectationhebergement,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$affectationhebergement->getIdhebergement(), $request->request->get('_token'))) {
            $entityManager->remove($affectationhebergement);
            $entityManager->flush();
            $this->addFlash('success', 'Suppression effectuée avec succès');
        }

        return $this->redirectToRoute('app_affectationhebergement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function paymentSuccess(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $pendingData = $session->get('pending_affectation');

        if ($pendingData && isset($pendingData['entity'])) {
            try {
                $entityManager->persist($pendingData['entity']);
                $entityManager->flush();
                $session->remove('pending_affectation');
                $this->addFlash('success', 'Paiement réussi et réservation confirmée');
            } catch (\Exception $e) {
                $this->logger->error('Failed to save after payment', ['error' => $e->getMessage()]);
                $this->addFlash('error', 'La réservation n\'a pas pu être enregistrée');
            }
        } else {
            $this->addFlash('warning', 'Aucune réservation en attente trouvée');
        }

        return $this->redirectToRoute('app_affectationhebergement_index');
    }

    #[Route('/payment/webhook', name: 'app_payment_webhook', methods: ['POST'])]
    public function paymentWebhook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->logger->info('Payment webhook received', ['data' => $data]);

        if (isset($data['status']) && $data['status'] === 'completed') {
            // Handle successful payment
            $this->logger->info('Payment completed', ['payment_data' => $data]);
            return new Response('Webhook processed', Response::HTTP_OK);
        }

        return new Response('Webhook received', Response::HTTP_OK);
    }

    private function processPayment(?float $prix, $user): ?string
    {
        $amount = $prix ? (int)($prix * 1000) : 10000; // Convert to millimes

        try {
            $httpClient = \Symfony\Component\HttpClient\HttpClient::create();
            
            $payload = [
                'receiverWalletId' => '681cbe6a70a82b8685c48795',
                'token' => 'TND',
                'amount' => $amount,
                'type' => 'immediate',
                'description' => 'Paiement hébergement',
                'acceptedPaymentMethods' => ['wallet', 'bank_card', 'e-DINAR'],
                'lifespan' => 10,
                'checkoutForm' => true,
                'addPaymentFeesToAmount' => true,
                'firstName' => $user->getPrenomutilisateur() ?? 'Client',
                'lastName' => $user->getNomutilisateur() ?? 'RH360',
                'phoneNumber' =>'22777777',
                'email' => $user->getEmailutilisateur(),
                'orderId' => 'heb_'.uniqid(),
                'theme' => 'dark'
            ];

            $response = $httpClient->request('POST', 'https://api.sandbox.konnect.network/api/v2/payments/init-payment', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'x-api-key' => '681cbe6a70a82b8685c4878d:swuS5O74bFJR5uiehGDbZ'
                ],
                'json' => $payload,
                'timeout' => 30
            ]);
            $content = $response->toArray();
            if (isset($content['payUrl'])) {
                return $content['payUrl'];
            }

            $this->logger->error('Payment API response missing payUrl', ['response' => $content]);
            return null;

        } catch (\Exception $e) {
            $this->logger->error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
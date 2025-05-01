<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Brancheentreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/entreprise')]
final class EntrepriseController extends AbstractController
{
    #[Route('/front', name: 'app_entreprise_index_front', methods: ['GET'])]
    public function indexFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search', '');
        $sortBy = $request->query->get('sort', 'nomentreprise');
        $sortOrder = $request->query->get('order', 'asc');

        $queryBuilder = $entityManager->getRepository(Entreprise::class)->createQueryBuilder('e');

        if ($search) {
            $queryBuilder->where('e.nomentreprise LIKE :search OR e.secteurentreprise LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('e.' . $sortBy, $sortOrder);

        $entreprises = $queryBuilder->getQuery()->getResult();

        return $this->render('entreprise/indexFront.html.twig', compact('entreprises', 'search', 'sortBy', 'sortOrder'));
    }

    #[Route('/front/{identreprise}', name: 'app_entreprise_show_front', methods: ['GET'])]
    public function showFront(Entreprise $entreprise): Response
    {
        $branches = array_unique($entreprise->getBrancheentreprises()->toArray(), SORT_REGULAR);

        return $this->render('entreprise/showFront.html.twig', [
            'entreprise' => $entreprise,
            'branches' => $branches,
            'departments' => $entreprise->getDepartmententreprises(),
        ]);
    }

    #[Route('/rh', name: 'app_branches_back', methods: ['GET'])]
    public function indexBack(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $branches = $entityManager->getRepository(Brancheentreprise::class)->findBy(['idutilisateur' => $user]);

        return $this->render('entreprise/indexBack.html.twig', compact('branches'));
    }

    #[Route('/list', name: 'app_entreprise_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search', '');
        $sortBy = $request->query->get('sort', 'nomentreprise');
        $sortOrder = $request->query->get('order', 'asc');

        $queryBuilder = $entityManager->getRepository(Entreprise::class)
            ->createQueryBuilder('e');

        // Appliquer le filtre de recherche
        if (!empty($search)) {
            $queryBuilder
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('e.nomentreprise', ':search'),
                    $queryBuilder->expr()->like('e.secteurentreprise', ':search')
                ))
                ->setParameter('search', '%' . $search . '%');
        }

        // Appliquer le tri
        $queryBuilder->orderBy('e.' . $sortBy, $sortOrder);

        $entreprises = $queryBuilder->getQuery()->getResult();

        // Calculer les statistiques
        $stats = $this->calculateStatistics($entityManager);

        // Si c'est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            return $this->render('entreprise/_table_body.html.twig', [
                'entreprises' => $entreprises
            ]);
        }

        // Pour le rendu initial de la page
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'stats' => $stats
        ]);
    }

    private function calculateStatistics(EntityManagerInterface $entityManager): array
    {
        $repository = $entityManager->getRepository(Entreprise::class);
        
        // Total des entreprises
        $totalEntreprises = $repository->createQueryBuilder('e')
            ->select('COUNT(e.identreprise)')
            ->getQuery()
            ->getSingleScalarResult();

        // Statistiques par secteur
        $secteurStats = $repository->createQueryBuilder('e')
            ->select('e.secteurentreprise, COUNT(e.identreprise) as count')
            ->groupBy('e.secteurentreprise')
            ->getQuery()
            ->getResult();

        // Entreprises créées par mois
        $entreprisesParMois = $repository->createQueryBuilder('e')
            ->select('COUNT(e.identreprise) as count')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalEntreprises' => $totalEntreprises,
            'secteurStats' => $secteurStats,
            'entreprisesParMois' => $entreprisesParMois
        ];
    }

    #[Route('/new', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logoentreprise')->getData();

            if ($file) {
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('logo_directory'), $fileName);
                $entreprise->setLogoentreprise($fileName);
            } else {
                $entreprise->setLogoentreprise('default-logo.png');
            }

            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index');
        }

        return $this->render('entreprise/new.html.twig', compact('entreprise', 'form'));
    }

    #[Route('/{identreprise}/edit', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logoentreprise')->getData();

            if ($file) {
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('logo_directory'), $fileName);
                $entreprise->setLogoentreprise($fileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index');
        }

        return $this->render('entreprise/edit.html.twig', compact('entreprise', 'form'));
    }

    #[Route('/{identreprise}', name: 'app_entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getIdentreprise(), $request->get('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entreprise_index');
    }

    #[Route('/back/{identreprise}', name: 'app_entreprise_show_back', methods: ['GET'])]
    public function showBack(Entreprise $entreprise): Response
    {
        $branches = array_unique($entreprise->getBrancheentreprises()->toArray(), SORT_REGULAR);

        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'branches' => $branches,
            'departments' => $entreprise->getDepartmententreprises(),
        ]);
    }

    #[Route('/front/{identreprise}/qr-code', name: 'app_entreprise_qr_code', methods: ['GET'])]
    public function generateQrCode(Entreprise $entreprise): Response
    {
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($this->generateUrl('app_entreprise_show_front', ['identreprise' => $entreprise->getIdentreprise()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->labelText($entreprise->getNomentreprise())
            ->labelFont(new NotoSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->build();

        return new Response($qrCode->getString(), 200, ['Content-Type' => 'image/png']);
    }

    #[Route('/front/{identreprise}/qr-code-page', name: 'app_entreprise_qr_code_page', methods: ['GET'])]
    public function qrCodePage(Entreprise $entreprise): Response
    {
        $qrData = [
            'nom' => $entreprise->getNomentreprise(),
            'description' => $entreprise->getDescriptionentreprise(),
            'url' => $entreprise->getUrlentreprise(),
            'secteur' => $entreprise->getSecteurentreprise(),
            'logo' => $this->getParameter('logo_directory') . '/' . $entreprise->getLogoentreprise()
        ];

        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data(json_encode($qrData))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->labelText($entreprise->getNomentreprise())
            ->labelFont(new OpenSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->build();

        return $this->render('entreprise/qrCodePage.html.twig', [
            'entreprise' => $entreprise,
            'qrCode' => $qrCode->getDataUri(),
            'qrData' => $qrData
        ]);
    }

    #[Route('/mobile/{identreprise}', name: 'app_entreprise_mobile_view', methods: ['GET'])]
    public function mobileView(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/mobileView.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

 
    #[Route('/list/pdf', name: 'app_entreprise_pdf', methods: ['GET'])]
    public function generatePdf(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données nécessaires
        $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        $stats = $this->calculateStatistics($entityManager);

        // Configurer DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        
        // Générer le HTML
        $html = $this->renderView('entreprise/pdf_template.html.twig', [
            'entreprises' => $entreprises,
            'stats' => $stats
        ]);

        // Charger le HTML dans DOMPDF
        $dompdf->loadHtml($html);
        
        // Configurer le format du papier
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Générer un nom de fichier avec la date
        $filename = 'rapport-entreprises-' . date('Y-m-d') . '.pdf';

        // Retourner le PDF comme réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );

}
    
}

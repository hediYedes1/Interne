<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BaseController extends AbstractController
{
    #[Route('/base', name: 'app_base')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/base1', name: 'app_base1')]
    public function index1(): Response
    {
        return $this->render('interviewFront.html.twig');
    }
    #[Route('/base2', name: 'app_base2')]
    public function index2(): Response
    {
        return $this->render('base1.html.twig');
    }

}

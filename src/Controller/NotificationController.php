<?php
// src/Controller/NotificationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NotificationController extends AbstractController
{
    // src/Controller/NotificationController.php
#[Route('/clear-notifications', name: 'clear_notifications')]
public function clearNotifications(): Response
{
    $filePath = $this->getParameter('kernel.project_dir').'/var/storage/notifications.json';
    
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    return $this->redirectToRoute('app_home');
}
#[Route('/notifications/json', name: 'notifications_json')]
public function getJson(): Response
{
    $filePath = $this->getParameter('kernel.project_dir') . '/var/storage/notifications.json';

    if (!file_exists($filePath)) {
        throw $this->createNotFoundException('Fichier non trouvé.');
    }

    $json = file_get_contents($filePath);
    return new Response($json, 200, ['Content-Type' => 'application/json']);
}
}
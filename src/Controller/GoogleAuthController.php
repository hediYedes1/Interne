<?PHP
// src/Controller/GoogleAuthController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\GoogleOAuthService;

class GoogleAuthController extends AbstractController
{
    #[Route('/auth/google/callback', name: 'google_auth_callback')]
    public function callback(Request $request, GoogleOAuthService $googleOAuthService): Response
    {
        $code = $request->query->get('code');
        if (!$code) {
            return new Response('Code d\'autorisation manquant', 400);
        }

        try {
            $googleOAuthService->exchangeCode($code);
            return new Response('Authentification réussie! Vous pouvez fermer cette page.');
        } catch (\Exception $e) {
            return new Response('Erreur d\'authentification: ' . $e->getMessage(), 400);
        }
    }
}
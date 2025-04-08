<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        // Get the logged-in user
        $user = $token->getUser();

        // Check the user's roles
        if (in_array('ROLE_CANDIDAT', $user->getRoles(), true)) {
            // Redirect to base2 for ADMIN
            return new RedirectResponse($this->router->generate('app_base'));
        }

        // Redirect to base for other roles
        return new RedirectResponse($this->router->generate('app_base2'));
    }
}
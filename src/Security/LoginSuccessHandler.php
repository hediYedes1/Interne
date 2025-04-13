<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use App\Enum\Role;

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

        $roles = $user->getRoles();

        // Check if the user has the CANDIDAT role
        if (in_array(Role::ADMIN->value, $roles, true)) {
            // Redirect to base2 for ADMIN
            return new RedirectResponse($this->router->generate('app_base2'));
        }

        if (in_array(Role::RH->value, $roles, true)) {
            // Redirect to base2 for ADMIN
            return new RedirectResponse($this->router->generate('app_base2'));
        }

        // Redirect to base for other roles
        return new RedirectResponse($this->router->generate('app_base'));
    }
}
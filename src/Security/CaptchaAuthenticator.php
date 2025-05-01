<?php

namespace App\Security;

use App\Service\CaptchaService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class CaptchaAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private CaptchaService $captchaService;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(CaptchaService $captchaService, UrlGeneratorInterface $urlGenerator)
    {
        $this->captchaService = $captchaService;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('_email');
        $password = $request->request->get('_password');
        $captchaInput = $request->request->get('_captcha');
        $captchaSolution = $request->request->get('_captcha_solution');

        if (empty($email) || empty($password)) {
            throw new CustomUserMessageAuthenticationException('Veuillez remplir tous les champs requis');
        }

        // If this is an AJAX request, we only validate credentials
        if ($request->isXmlHttpRequest()) {
            return new Passport(
                new UserBadge($email),
                new PasswordCredentials($password)
            );
        }

        // For regular form submission, validate CAPTCHA
        if (empty($captchaInput) || empty($captchaSolution)) {
            throw new CustomUserMessageAuthenticationException('Veuillez compléter le CAPTCHA');
        }

        if (!$this->captchaService->verifyCaptcha($captchaInput, $captchaSolution)) {
            throw new CustomUserMessageAuthenticationException('CAPTCHA invalide. Veuillez réessayer.');
        }

        // Create passport with credentials
        $passport = new Passport(
            new UserBadge($email),
            new PasswordCredentials($password)
        );

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // If this is an AJAX request, return JSON response
        if ($request->isXmlHttpRequest()) {
            return new Response(json_encode(['success' => true]), 200, ['Content-Type' => 'application/json']);
        }

        // For regular form submission, redirect based on role
        $user = $token->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_base1'));
        } elseif (in_array('ROLE_RH', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_base2'));
        } elseif (in_array('ROLE_CANDIDAT', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_base'));
        }

        // Default fallback
        return new RedirectResponse($this->urlGenerator->generate('app_base3'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->isXmlHttpRequest()) {
            return new Response(
                json_encode(['message' => $exception->getMessage()]),
                Response::HTTP_UNAUTHORIZED,
                ['Content-Type' => 'application/json']
            );
        }

        if ($request->hasSession()) {
            $request->getSession()->set('_security.last_error', $exception);
        }
        
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
} 
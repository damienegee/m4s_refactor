<?php

namespace App\Security;

use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use  TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

class AzureAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $router;
    private $ur;

    public function __construct(ClientRegistry $clientRegistry, RouterInterface $router, UserRepository $ur)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->ur = $ur;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === "connect_azure_check";
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getAzureClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var AzureResourceOwner $azureUser */
        $azureUser = $this->getAzureClient()->fetchUserFromToken($credentials);

        $email = $azureUser->getUpn();

        $user = $this->ur->findUserByEmail($email);

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $targetUrl = $this->router->generate('usernotfound');
        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        $targetUrl = $this->router->generate('home');
        return new RedirectResponse($targetUrl);
    }

    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/login', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    private function getAzureClient()
    {
        return $this->clientRegistry->getClient('azure');
    }
}

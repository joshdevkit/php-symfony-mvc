<?php

namespace Core\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager as BaseCsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class CsrfTokenManager
{
    private $csrfTokenManager;

    public function __construct(RequestStack $requestStack)
    {
        $session = $requestStack->getCurrentRequest()->getSession();

        $this->csrfTokenManager = new BaseCsrfTokenManager(
            new UriSafeTokenGenerator(),
            new SessionTokenStorage($session)
        );
    }

    public function getToken(string $tokenId): string
    {
        return $this->csrfTokenManager->getToken($tokenId)->getValue();
    }

    public function isTokenValid(string $tokenId, string $tokenValue): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($tokenId, $tokenValue));
    }
}

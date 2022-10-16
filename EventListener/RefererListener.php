<?php
declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Owl\Bundle\CoreBundle\Util\RefererPathTrait;
use Owl\Component\Core\Model\AdminUserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RefererListener
{
    use RefererPathTrait;

    private Session $sessionManager;

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        $currentUrl = $request->getRequestUri();
        $token = $this->tokenStorage->getToken();
        $user = $token !== null ? $token->getUser() : null;
        $hasReferer = $request->get('_sylius')['vars']['referer'] ?? false;

        if (null !== $token && $user instanceof AdminUserInterface && $request->isMethodSafe() && !$request->isXmlHttpRequest()) {
            if($hasReferer) {
                $this->saveRefererPath($session, $currentUrl);
            }
        }

        return;
    }
}

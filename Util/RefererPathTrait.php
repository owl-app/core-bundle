<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Util;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait RefererPathTrait
{
    private function saveRefererPath(SessionInterface $session, string $previousPath): void
    {
        $session->set('_previous_path', $previousPath);
    }
}

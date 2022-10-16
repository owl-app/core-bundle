<?php

namespace Owl\Bundle\CoreBundle\Util;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait RefererPathTrait
{
    private function saveRefererPath(SessionInterface $session, string $previousPath)
    {
        $session->set('_previous_path', $previousPath);
    }
}

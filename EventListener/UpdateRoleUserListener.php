<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Owl\Component\Core\Updater\SingleRoleUpdaterInterface;
use Owl\Component\Rbac\Model\RoleAwareInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class UpdateRoleUserListener
{
    public function __construct(private SingleRoleUpdaterInterface $roleUpdater)
    {
    }

    public function assingRole(GenericEvent $event): void
    {
        $subject = $event->getSubject();

        Assert::isInstanceOf($subject, RoleAwareInterface::class);

        $this->roleUpdater->assign($subject);
    }
}

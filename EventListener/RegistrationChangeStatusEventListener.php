<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Owl\Bundle\CoreBundle\Event\RegistrationEvents;
use Owl\Component\Core\Model\AdminUserRegistrationDataInterface;
use Owl\Component\Core\Updater\SingleRoleUpdaterInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RegistrationChangeStatusEventListener
{
    public function __construct(
        private EntityManagerInterface $adminUserRegistrationDataManager,
        private SingleRoleUpdaterInterface $roleUpdater,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function handleChangeStatusRegistration(GenericEvent $event): void
    {
        $adminUserRegistrationData = $event->getSubject();
        Assert::isInstanceOf($adminUserRegistrationData, AdminUserRegistrationDataInterface::class);

        $this->sendVerificationEmail($adminUserRegistrationData);
    }

    private function sendVerificationEmail(AdminUserRegistrationDataInterface $adminUserRegistrationData): void
    {
        $status = $adminUserRegistrationData->getStatus();

        if ($status === AdminUserRegistrationDataInterface::STATUS_ACCEPTED) {
            $this->roleUpdater->assign($adminUserRegistrationData->getUser());
        }

        $adminUserRegistrationData->setChangeStatusAt(new \DateTime());
        $adminUserRegistrationData->getUser()->setEnabled($status === AdminUserRegistrationDataInterface::STATUS_ACCEPTED);

        $this->adminUserRegistrationDataManager->persist($adminUserRegistrationData);
        $this->adminUserRegistrationDataManager->flush();

        $this->eventDispatcher->dispatch(new GenericEvent($adminUserRegistrationData), $this->getEventName($status));
    }

    public function getEventName(string $status): string
    {
        if ($status === AdminUserRegistrationDataInterface::STATUS_ACCEPTED) {
            return RegistrationEvents::POST_CHANGE_STATUS_ACCEPTED;
        }

        return RegistrationEvents::POST_CHANGE_STATUS_REJECTED;
    }
}

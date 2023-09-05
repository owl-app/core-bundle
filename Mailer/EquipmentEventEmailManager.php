<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Mailer;

use Owl\Component\Core\Model\EquipmentEventInterface;
use Owl\Component\Core\Model\EquipmentInterface;
use Owl\Component\Core\Repository\AdminUserRepositoryInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class EquipmentEventEmailManager implements EquipmentEventEmailManagerInterface
{
    public function __construct(
        private SenderInterface $emailSender,
        private AdminUserRepositoryInterface $adminUserRepository
    ) {
    }

    public function sendNotifyEmail(EquipmentEventInterface $equipmentEvent): void
    {
        /** @var EquipmentInterface $equipment */
        $equipment = $equipmentEvent->getEquipment();
        $emails = $this->prepareNotifyEmails($equipment);

        $this->emailSender->send(
            Emails::EMAIL_EQUIPMENT_EVENT_NOTIFY,
            $emails,
            [
                'equipment' => $equipment,
                'equipmentEvent' => $equipmentEvent,
            ]
        );
    }

    /**
     * @return (mixed|null|string)[]
     *
     * @psalm-return list{0: mixed|null|string, 1?: mixed|null|string,...}
     */
    private function prepareNotifyEmails(EquipmentInterface $equipment): array
    {
        $emails = [$equipment->getUser()->getEmail()];
        $adminsCompany = $this->adminUserRepository->findAdminsCompany($equipment->getCompany()->getId());

        foreach ($adminsCompany as $adminCompany) {
            $adminCompanyEmail = $adminCompany->getEmail();

            if (!in_array($adminCompanyEmail, $emails)) {
                $emails[] = $adminCompanyEmail;
            }
        }

        return $emails;
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Mailer;

use Owl\Component\Core\Model\EquipmentEventInterface;

interface EquipmentEventEmailManagerInterface
{
    public function sendNotifyEmail(EquipmentEventInterface $equipmentEvent): void;
}

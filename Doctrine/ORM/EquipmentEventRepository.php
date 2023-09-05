<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Owl\Component\Core\Model\EquipmentEventInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\EquipmentEventRepositoryInterface;

class EquipmentEventRepository extends EntityRepository implements EquipmentEventRepositoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function findForEquipment(string $equipmentId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('user')
            ->leftJoin('o.user', 'user')
            ->andWhere('o.equipment = :equipmentId')
            ->setParameter('equipmentId', $equipmentId)
        ;
    }

    public function findWaitingToSend(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.equipment', 'equipment')
            ->andWhere('o.notifyState = :notifyState')
            ->andWhere('o.dateNotify <= :dateNotify')
            ->setParameter('notifyState', EquipmentEventInterface::NOTIFY_STATE_WAITING)
            ->setParameter('dateNotify', date('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }
}

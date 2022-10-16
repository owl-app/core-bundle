<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Owl\Component\Core\Repository\EquipmentRefuelingRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class EquipmentRefuelingRepository extends EntityRepository implements EquipmentRefuelingRepositoryInterface
{
    public function findForEquipment(string $equipmentId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('user')
            ->leftJoin('o.user', 'user')
            ->andWhere('o.equipment = :equipmentId')
            ->setParameter('equipmentId', $equipmentId)
        ;
    }
}

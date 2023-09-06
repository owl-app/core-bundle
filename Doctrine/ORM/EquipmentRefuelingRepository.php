<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Owl\Component\Core\Repository\EquipmentRefuelingRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Model\EquipmentRefuelingInterface;

/**
 * @template T of EquipmentRefuelingInterface
 *
 * @implements EquipmentRefuelingRepositoryInterface<T>
 */
class EquipmentRefuelingRepository extends EntityRepository implements EquipmentRefuelingRepositoryInterface
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
}

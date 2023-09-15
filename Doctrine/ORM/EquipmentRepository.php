<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Owl\Component\Core\Model\EquipmentInterface;
use Owl\Component\Core\Repository\EquipmentRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @template T of EquipmentInterface
 *
 * @implements EquipmentRepositoryInterface<T>
 */
class EquipmentRepository extends EntityRepository implements EquipmentRepositoryInterface
{
    public function findByNamePartWithPermission(?string $phrase = '', int $limit = 10, QueryBuilder $queryBuilderCreated = null): ?array
    {
        $queryBuilder = $queryBuilderCreated ?? $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('REGEXP(o.name, :name) = true')
            ->andWhere('o.location IS NULL')
            ->setParameter('name', '^' . $phrase)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCompany($companyId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('o.company = :companyId')
            ->setParameter('companyId', $companyId)
        ;
    }
}

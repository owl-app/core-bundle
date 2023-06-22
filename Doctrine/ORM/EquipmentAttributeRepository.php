<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\EquipmentAttributeRepositoryInterface;

class EquipmentAttributeRepository extends EntityRepository implements EquipmentAttributeRepositoryInterface
{
    public function findByCategory($categoryId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('o.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
        ;
    }
}

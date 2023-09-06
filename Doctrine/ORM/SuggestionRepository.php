<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\SuggestionRepositoryInterface;
use Owl\Component\Suggestion\Model\SuggestionInterface;

/**
 * @template T of SuggestionInterface
 *
 * @implements SuggestionRepositoryInterface<T>
 */
class SuggestionRepository extends EntityRepository implements SuggestionRepositoryInterface
{
    public function findByIdWithOwner(array $ids, QueryBuilder $queryBuilder = null): array
    {
        $queryBuilder = $queryBuilder ?? $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->in('o.id', $ids))
            ->getQuery()
            ->getResult()
        ;
    }

    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('files')
            ->leftJoin('o.files', 'files')
        ;
    }
}

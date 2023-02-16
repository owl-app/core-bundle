<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\SuggestionStatusRepositoryInterface;

class SuggestionStatusRepository extends EntityRepository implements SuggestionStatusRepositoryInterface
{
    public function createHistoryListQueryBuilder(string $suggestionId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.statusSubject = :suggestionId')
            ->setParameter('suggestionId', $suggestionId)
            ->addOrderBy('o.createdAt', 'DESC')
        ;

        return $queryBuilder;
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Owl\Component\Core\Model\SuggestionInterface;
use Owl\Component\Core\Provider\StatusHistoryDataProvider;
use Owl\Component\Core\Provider\StatusHistoryDataProviderInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\SuggestionStatusRepositoryInterface;

class SuggestionStatusRepository extends EntityRepository implements SuggestionStatusRepositoryInterface
{
    public function createHistoryListQueryBuilder(SuggestionInterface $suggestion): StatusHistoryDataProviderInterface
    {
        $resources = $this->createQueryBuilder('o')
            ->andWhere('o.statusSubject = :suggestionId')
            ->setParameter('suggestionId', $suggestion->getId())
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return new StatusHistoryDataProvider($suggestion, $resources);
    }
}

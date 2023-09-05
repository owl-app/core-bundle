<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\NotificationAcceptedRepositoryInterface;

class NotificationAcceptedRepository extends EntityRepository implements NotificationAcceptedRepositoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function findByNotification($notificationId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('o.notification = :notificationId')
            ->setParameter('notificationId', $notificationId)
        ;
    }
}

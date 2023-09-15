<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Owl\Component\Core\Model\NotificationAcceptedInterface;
use Owl\Component\Core\Repository\NotificationAcceptedRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @template T of NotificationAcceptedInterface
 *
 * @implements NotificationAcceptedRepositoryInterface<T>
 */
class NotificationAcceptedRepository extends EntityRepository implements NotificationAcceptedRepositoryInterface
{
    public function findByNotification($notificationId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('o.notification = :notificationId')
            ->setParameter('notificationId', $notificationId)
        ;
    }
}

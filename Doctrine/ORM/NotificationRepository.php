<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\NotificationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Core\Repository\NotificationRepositoryInterface;

/**
 * @template T of NotificationInterface
 *
 * @implements NotificationRepositoryInterface<T>
 */
class NotificationRepository extends EntityRepository implements NotificationRepositoryInterface
{
    private function createAssignedQueryBuilder(AdminUserInterface $user, string $groupAssigned): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->where($qb->expr()->orX(
                $qb->expr()->eq('o.assignedGroup', ':assignedGroupAll'),
                $qb->expr()->eq('o.assignedGroup', ':assignedGroup')
            ))
            ->andWhere('o.user != :user')
            ->leftJoin('o.acceptedNotifications', 'an', Expr\Join::WITH, 'an.user = :user')
            ->setParameter('assignedGroupAll', NotificationInterface::GROUP_ASSIGNED_ALL)
            ->setParameter('assignedGroup', $groupAssigned)
            ->setParameter('user', $user)
            ->having('COUNT(an) = 0');
    }

    public function findNotAccepted(int $id, AdminUserInterface $user, string $groupAssigned): ?NotificationInterface
    {
        $qb = $this->createAssignedQueryBuilder($user, $groupAssigned);

        return $qb
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->orderBy('o.dateIssue', 'DESC')
            ->groupBy('o.id')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllNotAccepted(AdminUserInterface $user, string $groupAssigned): array
    {
        $qb = $this->createAssignedQueryBuilder($user, $groupAssigned);

        return $qb
            ->andWhere($qb->expr()->andX(
                $qb->expr()->lte('o.currentFrom', ':dateFrom'),
                $qb->expr()->gte('o.currentTo', ':dateTo')
            ))
            ->setParameter('dateFrom', date('Y-m-d'))
            ->setParameter('dateTo', date('Y-m-d'))
            ->orderBy('o.dateIssue', 'DESC')
            ->groupBy('o.id')
            ->getQuery()
            ->getResult();
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Owl\Bundle\UserBundle\Doctrine\ORM\UserRepository;
use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\RoleAwareInterface;
use Owl\Component\Core\Repository\AdminUserRepositoryInterface;

/**
 * @template T of AdminUserInterface
 *
 * @extends UserRepository<T>
 *
 * @implements AdminUserRepositoryInterface<T>
 */
class AdminUserRepository extends UserRepository implements AdminUserRepositoryInterface
{
    public function findByCompany($companyId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('o.company = :companyId')
            ->setParameter('companyId', $companyId)
        ;
    }

    public function findAdminsCompany($companyId): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.role', 'role')
            ->leftJoin('role.setting', 'setting')
            ->andWhere('o.company = :companyId')
            ->andWhere('setting.canonicalName = :roleCanonicalName')
            ->setParameter('companyId', $companyId)
            ->setParameter('roleCanonicalName', RoleAwareInterface::ROLE_ADMIN_COMPANY_NAME)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRoleUser(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('role')
            ->addSelect('registration')
            ->leftJoin('o.role', 'role')
            ->leftJoin('role.setting', 'setting')
            ->leftJoin('o.registration', 'registration')
            ->andWhere('setting.canonicalName = :roleCanonicalName')
            ->setParameter('roleCanonicalName', RoleAwareInterface::ROLE_USER_NAME)
        ;
    }

    public function findEnabledWithOwner(?int $userId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if($userId) {
            $queryBuilder
                ->orWhere('o.id = :userId')
                ->setParameter('userId', $userId)
            ;
        }
        
        $queryBuilder
            ->orWhere('o.enabled = :enabled')
            ->setParameter('enabled', 1)
        ;

        return $queryBuilder;
    }
}

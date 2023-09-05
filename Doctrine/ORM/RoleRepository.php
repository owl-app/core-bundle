<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM;

use Owl\Component\Core\Model\RoleAwareInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Owl\Component\Rbac\Repository\RoleRepositoryInterface;
use Owl\Component\Rbac\Model\RoleInterface;

/**
 * @template T of RoleInterface
 *
 * @implements RoleRepositoryInterface<T>
 */
class RoleRepository extends EntityRepository implements RoleRepositoryInterface
{
    public function findWithoutAdminSystem(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.setting', 'setting')
            ->andWhere('setting.canonicalName != :canonicalName')
            ->setParameter('canonicalName', RoleAwareInterface::ROLE_ADMIN_SYSTEM_NAME)
            ->getQuery()
            ->getResult()
        ;
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Owl\Component\Rbac\Model\PermissionInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class RbacPermissionExampleFactory implements ExampleFactoryInterface
{
    public function __construct(
        private FactoryInterface $rbacPermissionFactory
    ) {
    }

    public function create(array $options = []): PermissionInterface
    {
        /** @var PermissionInterface $rbacPermission */
        $rbacPermission = $this->rbacPermissionFactory->createNew();
        $rbacPermission->setName($options['name']);
        $rbacPermission->setGroupPermission($options['group']);
        $rbacPermission->setDescription($options['description']);

        return $rbacPermission;
    }
}

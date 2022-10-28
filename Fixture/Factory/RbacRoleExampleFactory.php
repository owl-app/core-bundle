<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Faker\Generator;
use Faker\Factory;
use Owl\Component\Core\Model\Rbac\RoleInterface;
use Owl\Component\Core\Model\Rbac\RoleSetting;
use Owl\Component\Rbac\Provider\RoutesPermissionProviderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Factory\FactoryInterface;

class RbacRoleExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $rbacRoleFactory,
        private ReferenceRepository $permissionReference,
        private RoutesPermissionProviderInterface $routesPermissionProvider
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): RoleInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var RoleInterface $rbacRole */
        $rbacRole = $this->rbacRoleFactory->createNew();
        $rbacRole->setName($options['name']);
        $rbacRole->setDescription($options['description']);

        if (isset($options['setting']) && null !== $options['setting']) {
            $shopBillingData = new RoleSetting();
            $shopBillingData->setCanonicalName($options['setting']['canonical_name'] ?? null);
            $shopBillingData->setTheme($options['setting']['theme'] ?? null);

            $rbacRole->setSetting($shopBillingData);
        }

        if($options['all_permissions']) {
            $this->assignAllPermissions($rbacRole);
        }

        return $rbacRole;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined('name')
            ->setDefault('description', function (Options $options): string {
                return $this->faker->sentence();
            })
            ->setDefined('setting')
            ->setAllowedTypes('setting', ['array'])
            ->setDefault('all_permissions', false)
        ;
    }

    private function assignAllPermissions(RoleInterface $role): void
    {
        $routes = $this->routesPermissionProvider->getPermissions();

        foreach ($routes as $name => $route) {
            if ($this->permissionReference->hasReference($name)) {
                $role->addPermission($this->permissionReference->getReference($name));
            }
        }
    }
}

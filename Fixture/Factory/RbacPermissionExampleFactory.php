<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Faker\Generator;
use Faker\Factory;
use Owl\Component\Rbac\Model\PermissionInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Factory\FactoryInterface;

class RbacPermissionExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $rbacPermissionFactory
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): PermissionInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var PermissionInterface $rbacRole */
        $rbacPermission = $this->rbacPermissionFactory->createNew();
        $rbacPermission->setName($options['name']);
        $rbacPermission->getGroupPermission($options['group']);
        $rbacPermission->setDescription($options['description']);

        return $rbacPermission;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined('name')
            ->setDefined('group')
            ->setDefault('description', function (Options $options): string {
                return $this->faker->sentence();
            })
        ;
    }
}

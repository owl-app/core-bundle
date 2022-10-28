<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Doctrine\Persistence\ObjectManager;
use Owl\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Owl\Component\Rbac\Provider\RoutesPermissionProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class RbacPermissionFixture implements FixtureInterface
{
    public function __construct(
        private ObjectManager $objectManager,
        private ExampleFactoryInterface $exampleFactory,
        private RoutesPermissionProviderInterface $routesPermissionProvider,
        private ?ReferenceRepository $referenceRepository = null
    ) {
        $this->objectManager = $objectManager;
        $this->exampleFactory = $exampleFactory;
    }

    public function getName(): string
    {
        return 'rbac_permission';
    }

    final public function load(array $options): void
    {
        $routes = $this->routesPermissionProvider->getPermissions();
        $resourceReferences = [];
        $i = 0;

        foreach($routes as $name => $route) {
            $route['name'] = $name;
            $resource = $this->exampleFactory->create($route);

            $this->objectManager->persist($resource);

            ++$i;
    
            if (0 === ($i % 10)) {
                $this->objectManager->flush();
            }

            $resourceReferences[$name] = $resource;
        }

        $this->objectManager->flush();

        $this->addReferences($resourceReferences);

        $this->objectManager->clear();
    }

    final public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->getName());

        return $treeBuilder;
    }

    private function addReferences(array $resourceReferences): void
    {
        if ($resourceReferences) {
            foreach ($resourceReferences as $name => $resource) {
                $this->referenceRepository->addReference($name, $resource);
            }
        }
    }
}

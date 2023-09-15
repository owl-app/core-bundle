<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Owl\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractResourceFixture implements FixtureInterface
{
    private OptionsResolver $optionsResolver;

    public function __construct(
        private ObjectManager $objectManager,
        private ExampleFactoryInterface $exampleFactory,
        private ?ReferenceRepository $referenceRepository = null,
    ) {
        $this->objectManager = $objectManager;
        $this->exampleFactory = $exampleFactory;

        $this->optionsResolver =
            (new OptionsResolver())
                ->setDefault('random', 0)
                ->setAllowedTypes('random', 'int')
                ->setDefault('prototype', [])
                ->setAllowedTypes('prototype', 'array')
                ->setDefault('custom', [])
                ->setAllowedTypes('custom', 'array')
                ->setNormalizer('custom', function (Options $options, array $custom) {
                    if ($options['random'] <= 0) {
                        return $custom;
                    }

                    return array_merge($custom, array_fill(0, $options['random'], $options['prototype']));
                })
        ;
    }

    final public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);
        $references = $this->prepareReferences($options);
        $resourceReferences = [];

        $i = 0;
        foreach ($options['custom'] as $name => $resourceOptions) {
            $resource = $this->exampleFactory->create($resourceOptions);

            $this->objectManager->persist($resource);

            if (in_array($name, $references)) {
                $resourceReferences[$name] = $resource;
            }

            ++$i;

            if (0 === ($i % 10)) {
                $this->objectManager->flush();
            }
        }

        $this->objectManager->flush();

        $this->addReferences($resourceReferences);

        $this->objectManager->clear();
    }

    final public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->getName());

        /** @var ArrayNodeDefinition $optionsNode */
        $optionsNode = $treeBuilder->getRootNode();

        $optionsNode->children()
            ->integerNode('random')->min(0)->defaultValue(0)->end()
            ->variableNode('prototype')->end()
        ;

        /** @var ArrayNodeDefinition $resourcesNode */
        $resourcesNode = $optionsNode->children()->arrayNode('custom');

        /** @var ArrayNodeDefinition $resourceNode */
        $resourceNode = $resourcesNode->requiresAtLeastOneElement()->arrayPrototype();
        $this->configureResourceNode($resourceNode);

        $resourceNode
            ->children()
                ->scalarNode('reference')->defaultFalse()->end()
        ;

        return $treeBuilder;
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        // empty
    }

    /**
     * @psalm-return list<mixed>
     */
    private function prepareReferences(array &$options): array
    {
        $references = [];

        foreach ($options['custom'] as $name => $resourceOptions) {
            if ($resourceOptions['reference']) {
                $references[] = $name;
            }

            unset($options['custom'][$name]['reference']);
        }

        return $references;
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

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection\Compiler;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Sylius\Bundle\FixturesBundle\DependencyInjection\Compiler\FixtureRegistryPass;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class FixtureReferenceRegistryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('sylius.resource_registry')) {
            return;
        }

        $resourceRegistry = $container->get('sylius.resource_registry');

        $taggedServices = $container->findTaggedServiceIds(FixtureRegistryPass::FIXTURE_SERVICE_TAG);

        if ($taggedServices) {
            $referenceDefinitions = [];

            foreach ($taggedServices as $id => $attributes) {
                $referenceResource = null;

                foreach ($attributes as $attribute) {
                    if (isset($attribute['reference-resource'])) {
                        $referenceResource = $attribute['reference-resource'];
                    }
                }

                if (null !== $referenceResource) {
                    $definitionFixture = $container->findDefinition($id);
                    $metadata = $resourceRegistry->get($referenceResource);
                    $referenceSerivceName = $this->getReferenceServiceName($metadata);
                    $referenceDefinition = $this->getReferenceDefinition($metadata);

                    $referenceDefinitions[$referenceSerivceName] = $referenceDefinition;

                    $container->addDefinitions($referenceDefinitions);

                    $definitionFixture->addArgument(new Reference($referenceSerivceName));
                }
            }
        }
    }

    private function getReferenceDefinition(MetadataInterface $metadata): Definition
    {
        $definition = new Definition(ReferenceRepository::class);
        $definition
            ->setArguments([new Reference($metadata->getServiceId('manager'))])
        ;

        return $definition;
    }

    private function getReferenceServiceName(MetadataInterface $metadata): string
    {
        return $metadata->getApplicationName() . '.fixture.reference.' . $metadata->getName();
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class CompanyFixture extends AbstractResourceFixture
{
    /**
     * @psalm-return 'company'
     */
    public function getName(): string
    {
        return 'company';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('nip')->cannotBeEmpty()->end()
                ->scalarNode('city')->end()
                ->scalarNode('street')->end()
                ->scalarNode('post_code')->cannotBeEmpty()->end()
                ->scalarNode('phone')->cannotBeEmpty()->end()
                ->scalarNode('email')->cannotBeEmpty()->end()
        ;
    }
}

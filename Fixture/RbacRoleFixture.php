<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class RbacRoleFixture extends AbstractResourceFixture
{
    /**
     * @return string
     *
     * @psalm-return 'rbac_role'
     */
    public function getName(): string
    {
        return 'rbac_role';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('description')->end()
                ->arrayNode('setting')
                    ->children()
                        ->scalarNode('canonical_name')->end()
                        ->scalarNode('theme')->end()
                    ->end()
                ->end()
                ->booleanNode('all_permissions')->end()
        ;
    }
}

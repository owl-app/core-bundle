<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class AdminUserFixture extends AbstractResourceFixture
{
    public function getName(): string
    {
        return 'admin_user';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('role_reference')->cannotBeEmpty()->end()
                ->scalarNode('company_reference')->cannotBeEmpty()->end()
                ->scalarNode('display_name')->cannotBeEmpty()->end()
                ->scalarNode('first_name')->cannotBeEmpty()->end()
                ->scalarNode('last_name')->cannotBeEmpty()->end()
                ->scalarNode('phone')->cannotBeEmpty()->end()
                ->scalarNode('email')->cannotBeEmpty()->end()
                ->booleanNode('enabled')->end()
                ->scalarNode('role')->cannotBeEmpty()->end()
                ->scalarNode('locale_code')->end()
                ->scalarNode('password')->end()
        ;
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class SettingFixture extends AbstractResourceFixture
{
    /**
     * @return string
     *
     * @psalm-return 'setting'
     */
    public function getName(): string
    {
        return 'setting';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('section')->cannotBeEmpty()->end()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('value')->cannotBeEmpty()->end()
                ->scalarNode('lang')->cannotBeEmpty()->end()
        ;
    }
}

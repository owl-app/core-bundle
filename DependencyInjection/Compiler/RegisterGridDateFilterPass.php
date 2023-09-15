<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection\Compiler;

use Owl\Bundle\CoreBundle\Form\Type\Grid\Filter\DateFilterType;
use Sylius\Bundle\GridBundle\Form\Type\Filter\DateFilterType as DateTimeFilterType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterGridDateFilterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sylius.registry.grid_filter') || !$container->hasDefinition('sylius.form_registry.grid_filter')) {
            return;
        }

        $registry = $container->getDefinition('sylius.registry.grid_filter');
        $formTypeRegistry = $container->getDefinition('sylius.form_registry.grid_filter');

        $registry->addMethodCall('unregister', ['date']);

        $registry->addMethodCall('register', ['datetime', new Reference('sylius.grid_filter.date')]);
        $formTypeRegistry->addMethodCall('add', ['datetime', 'default', DateTimeFilterType::class]);

        $registry->addMethodCall('register', ['date', new Reference('owl.grid_filter.date')]);
        $formTypeRegistry->addMethodCall('add', ['date', 'default', DateFilterType::class]);
    }
}

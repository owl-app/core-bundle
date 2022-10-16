<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection\Compiler;

use Owl\Bridge\SyliusResourceBridge\Event\CollectionPreLoadEvent;
use Owl\Component\Core\Model\Authorization\OwnerableCompanyInterface;
use Owl\Component\Core\Model\Authorization\OwnerableUserInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

final class RegisterListenerOwnerableCollectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        try {
            $resourceRegistry = $container->get('sylius.resource_registry');
            $definitionListener = $container->findDefinition('owl.listener.ownerable_permission_collection');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        foreach ($resourceRegistry->getAll() as $alias => $resource) {
            $model = $resource->getParameters()['classes']['model'];

            if (\is_subclass_of($model, OwnerableCompanyInterface::class) || \is_subclass_of($model, OwnerableUserInterface::class)) {
                $definitionListener->addTag('kernel.event_listener', [
                    'event' => sprintf('%s.%s.%s', $resource->getApplicationName(), $resource->getName(), CollectionPreLoadEvent::EVENT_NAME),
                    'method' => 'addCondition',
                ]);
            }
        }
    }
}

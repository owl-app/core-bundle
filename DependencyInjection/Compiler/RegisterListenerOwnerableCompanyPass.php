<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection\Compiler;

use Owl\Component\Core\Model\Authorization\OwnerableCompanyInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

final class RegisterListenerOwnerableCompanyPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        try {
            $resourceRegistry = $container->get('sylius.resource_registry');
            $definitionListener = $container->findDefinition('owl.listener.add_company');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        foreach ($resourceRegistry->getAll() as $alias => $resource) {
            if(\is_subclass_of($resource->getParameters()['classes']['model'], OwnerableCompanyInterface::class)) {
                $definitionListener->addTag('kernel.event_listener', [
                    'event' => $alias.'.pre_create',
                    'method' => 'addCompany',
                ]);
            }
        }
    }
}

<?php

namespace Owl\Bundle\CoreBundle;

use Owl\Bridge\SyliusResourceBridge\DependencyInjection\Compiler\ActionsResourcePass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\CompositeOwnerableConditionPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\FixtureReferenceRegistryPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterListenerOwnerableCollectionPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterListenerOwnerableCompanyPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterGridDateFilterPass;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class OwlCoreBundle extends AbstractResourceBundle
{
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterGridDateFilterPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new ActionsResourcePass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new FixtureReferenceRegistryPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new CompositeOwnerableConditionPass());
        $container->addCompilerPass(new RegisterListenerOwnerableCompanyPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new RegisterListenerOwnerableCollectionPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
    }

    protected function getModelNamespace(): string
    {
        return 'Owl\Component\Core\Model';
    }
}

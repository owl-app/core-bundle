<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle;

use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\BackwardsCompatibility\Symfony6PrivateServicesPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\FixtureReferenceRegistryPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterGridDateFilterPass;
use Owl\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterListenerOwnerableCompanyPass;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class OwlCoreBundle extends AbstractResourceBundle
{
    /**
     * @return string[]
     *
     * @psalm-return list{'doctrine/orm'}
     */
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
        $container->addCompilerPass(new FixtureReferenceRegistryPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new RegisterListenerOwnerableCompanyPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new Symfony6PrivateServicesPass());
    }

    /**
     * @psalm-return 'Owl\Component\Core\Model'
     */
    protected function getModelNamespace(): string
    {
        return 'Owl\Component\Core\Model';
    }
}

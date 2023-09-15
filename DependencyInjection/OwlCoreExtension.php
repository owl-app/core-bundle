<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class OwlCoreExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /** @var array */
    private static $bundles = [
        'sylius_addressing',
        'sylius_attribute',
        'sylius_channel',
        'sylius_currency',
        'sylius_customer',
        'sylius_inventory',
        'sylius_locale',
        'sylius_order',
        'sylius_payment',
        'sylius_payum',
        'sylius_product',
        'sylius_promotion',
        'sylius_shipping',
        'sylius_taxation',
        'sylius_taxonomy',
        'owl_user',
        'owl_review',
        'owl_service',
        'owl_city',
        'sylius_variation',
    ];

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->registerResources('owl', $config['driver'], $config['resources'], $container);

        $loader->load('services.xml');

        $env = $container->getParameter('kernel.environment');
        if ('test' === $env || 'test_cached' === $env) {
            $loader->load('test_services.xml');
        }

        $loader->load(sprintf('integrations/%s.xml', $config['driver']));
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);

        $this->prependSyliusThemeBundle($container, $config['driver']);
        $this->prependHwiOauth($container);
        $this->prependDoctrineMigrations($container);
        $this->prependJmsSerializerIfAdminApiBundleIsNotPresent($container);
        $this->prependSyliusResourceBundle($container, $config);
    }

    /**
     * @psalm-return 'Owl\Bundle\CoreBundle\Migrations'
     */
    protected function getMigrationsNamespace(): string
    {
        return 'Owl\Bundle\CoreBundle\Migrations';
    }

    /**
     * @psalm-return '@OwlCoreBundle/Migrations'
     */
    protected function getMigrationsDirectory(): string
    {
        return '@OwlCoreBundle/Migrations';
    }

    /**
     * @psalm-return array<never, never>
     */
    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [];
    }

    private function prependHwiOauth(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('hwi_oauth')) {
            return;
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services/integrations/hwi_oauth.xml');
    }

    private function prependSyliusResourceBundle(ContainerBuilder $container, array $config): void
    {
        $container->prependExtensionConfig('sylius_resource', [
            'authorization_checker' => $config['authorization_checker'],
        ]);
    }

    private function prependSyliusThemeBundle(ContainerBuilder $container, string $driver): void
    {
        if (!$container->hasExtension('sylius_theme')) {
            return;
        }

        foreach ($container->getExtensions() as $name => $extension) {
            if (in_array($name, self::$bundles, true)) {
                $container->prependExtensionConfig($name, ['driver' => $driver]);
            }
        }

        $container->prependExtensionConfig('sylius_theme', ['context' => 'owl.theme.context.role_based']);
    }

    private function prependJmsSerializerIfAdminApiBundleIsNotPresent(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('jms_serializer')) {
            return;
        }

        if ($container->hasExtension('owl_admin_api')) {
            return;
        }

        $container->prependExtensionConfig('jms_serializer', [
            'metadata' => [
                'directories' => [
                    'owl-core' => [
                        'namespace_prefix' => 'Owl\Component\Core',
                        'path' => '@OwlCoreBundle/Resources/config/serializer',
                    ],
                    'owl-core-rbac' => [
                        'namespace_prefix' => 'Owl\Component\Rbac',
                        'path' => '@OwlCoreBundle/Resources/config/serializer/rbac',
                    ],
                ],
            ],
            'property_naming' => [
                'id' => 'jms_serializer.identical_property_naming_strategy',
            ],
        ]);
    }
}

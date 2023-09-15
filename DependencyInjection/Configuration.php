<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection;

use Owl\Bridge\SyliusResource\Controller\BaseController;
use Owl\Bundle\CoreBundle\Controller\UserController;
use Owl\Bundle\CoreBundle\Doctrine\ORM\EquipmentEventRepository;
use Owl\Bundle\CoreBundle\Doctrine\ORM\EquipmentRefuelingRepository;
use Owl\Bundle\CoreBundle\Doctrine\ORM\NotificationAcceptedRepository;
use Owl\Bundle\CoreBundle\Form\Type\Equipment\EquipmentEventType;
use Owl\Bundle\CoreBundle\Form\Type\Equipment\EquipmentRefuelingType;
use Owl\Component\Core\Model\AdminUserRegistrationData;
use Owl\Component\Core\Model\EquipmentEvent;
use Owl\Component\Core\Model\EquipmentRefueling;
use Owl\Component\Core\Model\NotificationAccepted;
use Owl\Component\Core\Model\Rbac\RoleSetting;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('owl_core');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->booleanNode('prepend_doctrine_migrations')->defaultTrue()->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->scalarNode('authorization_checker')
                    ->defaultValue('owl.resource_controller.authorization_checker')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('role_settings')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(RoleSetting::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('admin_user_permission')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(UserController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('admin_user_registration_data')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(AdminUserRegistrationData::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(BaseController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('notification_accepted')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('controller')->defaultValue(BaseController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('model')->defaultValue(NotificationAccepted::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(NotificationAcceptedRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('equipment_event')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(EquipmentEvent::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(BaseController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EquipmentEventRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(EquipmentEventType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('equipment_refueling')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(EquipmentRefueling::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(BaseController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EquipmentRefuelingRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(EquipmentRefuelingType::class)->cannotBeEmpty()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                ->end()
            ->end()
        ;
    }
}

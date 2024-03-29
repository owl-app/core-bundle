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

namespace Owl\Bundle\CoreBundle\Form\Type\Grid\Filter;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @experimental
 */
final class EntitiesFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'class' => null,
                'label' => false,
                'placeholder' => 'sylius.ui.all',
            ])
        ;
    }

    /**
     * @psalm-return EntityType::class
     */
    public function getParent(): string
    {
        return EntityType::class;
    }

    /**
     * @psalm-return 'sylius_grid_filter_entities'
     */
    public function getBlockPrefix(): string
    {
        return 'sylius_grid_filter_entities';
    }
}

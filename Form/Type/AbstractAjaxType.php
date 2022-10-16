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

namespace Owl\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Valid;
use Webmozart\Assert\Assert;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface as FormFormBuilderInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;

final class AbstractAjaxType extends AbstractResourceType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormFormBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}

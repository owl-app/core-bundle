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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface as FormFormBuilderInterface;

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
     *
     * @psalm-return FormType::class
     */
    public function getExtendedType(): string
    {
        return FormType::class;
    }
}

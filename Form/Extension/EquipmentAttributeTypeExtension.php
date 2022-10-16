<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Extension;

use Owl\Bundle\CoreBundle\Form\Type\EquipmentCategoryChoiceType;
use Owl\Bundle\EquipmentBundle\Form\Type\EquipmentAttributeType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class EquipmentAttributeTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('category', EquipmentCategoryChoiceType::class, [
                'label' => 'owl.form.equipment.category',
            ])
        ;
    }

    public function getExtendedType(): string
    {
        return EquipmentAttributeType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [EquipmentAttributeType::class];
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Owl\Bundle\CategoryBundle\Form\Type\CategoryType;

final class EquipmentCategoryType extends CategoryType
{
    public function getBlockPrefix(): string
    {
        return 'owl_equipment_category';
    }
}

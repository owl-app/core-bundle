<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Owl\Bundle\FileBundle\Form\Type\FileType;

final class EquipmentFileType extends FileType
{
    /**
     * @psalm-return 'owl_equipment_file'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_equipment_file';
    }
}

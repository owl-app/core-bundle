<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Owl\Bundle\FileBundle\Form\Type\UploadFileType;

final class EquipmentUploadFileType extends UploadFileType
{
    public function getBlockPrefix(): string
    {
        return 'owl_equipment_upload_file';
    }
}

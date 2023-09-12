<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Notification;

use Owl\Bundle\FileBundle\Form\Type\FileType;

final class NotificationFileType extends FileType
{
    public function getBlockPrefix(): string
    {
        return 'owl_notification_file';
    }
}

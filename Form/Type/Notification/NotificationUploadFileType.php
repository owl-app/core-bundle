<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Notification;

use Owl\Bundle\FileBundle\Form\Type\UploadFileType;

final class NotificationUploadFileType extends UploadFileType
{
    public function getBlockPrefix(): string
    {
        return 'owl_notification_upload_file';
    }
}

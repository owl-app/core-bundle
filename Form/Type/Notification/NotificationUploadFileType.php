<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Notification;

use Owl\Bundle\FileBundle\Form\Type\UploadFileType;

final class NotificationUploadFileType extends UploadFileType
{
    /**
     * @return string
     *
     * @psalm-return 'owl_notification_upload_file'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_notification_upload_file';
    }
}

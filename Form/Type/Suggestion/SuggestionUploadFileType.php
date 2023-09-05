<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Suggestion;

use Owl\Bundle\FileBundle\Form\Type\UploadFileType;

final class SuggestionUploadFileType extends UploadFileType
{
    /**
     * @return string
     *
     * @psalm-return 'owl_suggestion_upload_file'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_suggestion_upload_file';
    }
}

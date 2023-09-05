<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Suggestion;

use Owl\Bundle\FileBundle\Form\Type\FileType;

final class SuggestionFileType extends FileType
{
    /**
     * @return string
     *
     * @psalm-return 'owl_suggestion_file'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_suggestion_file';
    }
}

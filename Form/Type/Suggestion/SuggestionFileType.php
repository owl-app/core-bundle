<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Suggestion;

use Owl\Bundle\FileBundle\Form\Type\FileType;

final class SuggestionFileType extends FileType
{
    public function getBlockPrefix(): string
    {
        return 'owl_suggestion_file';
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Suggestion;

use Owl\Bundle\StatusBundle\Form\Type\StatusType;

final class SuggestionStatusType extends StatusType
{
    /**
     * @psalm-return 'owl_suggestion_status'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_suggestion_status';
    }
}

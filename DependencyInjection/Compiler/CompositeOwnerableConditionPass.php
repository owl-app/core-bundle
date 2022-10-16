<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class CompositeOwnerableConditionPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'owl.authorization.owner.condition',
            'owl.authorization.owner.condition.composite',
            'owl.authorization.owner.condition',
            'addCondition'
        );
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Owl\Component\Core\Authorization\Owner\OwnerConditionProviderInterface;
use Owl\Bridge\SyliusResourceBridge\Event\CollectionPreLoadEvent;

final class OwnerableResourceCollectionListener
{
    private OwnerConditionProviderInterface $ownerConditionProvider;

    public function __construct(OwnerConditionProviderInterface $ownerConditionProvider)
    {
        $this->ownerConditionProvider = $ownerConditionProvider;
    }

    public function addCondition(CollectionPreLoadEvent $event): void
    {
        $dataClass = $event->getDataClass();
        $expressionBuilder = $event->getExpressionBuilder();
        $ownerCondition = $this->ownerConditionProvider->provide($dataClass);

        if($ownerCondition) {
            if($expressionBuilder) {
                $expression = $ownerCondition->getExpression($expressionBuilder);

                $event->addExpression($expression);
            } else {
                $event->addCriteria($ownerCondition->getCriteria());
            }
        }
    }
}

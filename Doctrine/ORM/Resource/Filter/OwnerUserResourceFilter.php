<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM\Resource\Filter;

use Doctrine\Orm\QueryBuilder;
use Owl\Bridge\SyliusResource\Doctrine\Orm\Filter\FilterInterface;
use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Model\Authorization\OwnerableUserInterface;
use Sylius\Bundle\GridBundle\Doctrine\ORM\ExpressionBuilder;

final class OwnerUserResourceFilter implements FilterInterface
{
    public function __construct(private AdminUserContextInterface $adminUserContext)
    {
    }

    public function support(string $resourceClass, string $action): bool
    {
        if (is_subclass_of($resourceClass, OwnerableUserInterface::class) && $this->adminUserContext->isUser()) {
            return true;
        }

        return false;
    }

    /** @psalm-suppress InvalidClass */
    public function apply(QueryBuilder $queryBuilder, string $model): void
    {
        $expressionBuilder = new ExpressionBuilder($queryBuilder);

        $queryBuilder->andWhere(
            $expressionBuilder->equals('user', $this->adminUserContext->getUser()),
        );
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM\Resource\Filter;

use Doctrine\Orm\QueryBuilder;
use Owl\Bridge\SyliusResource\Doctrine\Orm\Filter\FilterInterface;
use Owl\Component\Company\Model\CompanyInterface;
use Owl\Component\Core\Context\AdminUserContextInterface;
use Sylius\Bundle\GridBundle\Doctrine\ORM\ExpressionBuilder;

final class CompanyResourceFilter implements FilterInterface
{
    public function __construct(private AdminUserContextInterface $adminUserContext)
    {
    }

    public function support(string $resourceClass, string $action): bool
    {
        if (
            is_subclass_of($resourceClass, CompanyInterface::class) &&
            ($this->adminUserContext->isAdminCompany() || $this->adminUserContext->isUser())
        ) {
            return true;
        }

        return false;
    }

    /** @psalm-suppress InvalidClass */
    public function apply(QueryBuilder $queryBuilder, string $model): void
    {
        $expressionBuilder = new ExpressionBuilder($queryBuilder);

        $queryBuilder->andWhere(
            $expressionBuilder->in('id', $this->adminUserContext->getAccessCompaniesIds()),
        );
    }
}

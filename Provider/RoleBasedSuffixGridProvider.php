<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Provider;

use Owl\Component\Core\Model\RoleAwareInterface;
use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Provider\SuffixGridProviderInterface;

final class RoleBasedSuffixGridProvider implements SuffixGridProviderInterface
{
    public const ROLE_SUFFIX = [
        RoleAwareInterface::ROLE_ADMIN_COMPANY_NAME => 'company',
        RoleAwareInterface::ROLE_USER_NAME => 'user'
    ];

    private AdminUserContextInterface $adminUserContext;

    public function __construct(AdminUserContextInterface $adminUserContext)
    {
        $this->adminUserContext = $adminUserContext;
    }

    public function getSuffix(): string
    {
        $roleName = $this->adminUserContext->getRoleCanonicalName();

        if (isset(self::ROLE_SUFFIX[$roleName ])) {
            return '_role_'.self::ROLE_SUFFIX[$roleName ];
        }

        return '';
    }
}

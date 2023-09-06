<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Context;

use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\CompanyInterface;
use Owl\Component\Core\Model\Rbac\RoleInterface;
use Owl\Component\Core\Model\Rbac\RoleSettingInterface;
use Owl\Component\Core\Model\RoleAwareInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

final class AdminUserContext implements AdminUserContextInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): ?AdminUserInterface
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $user = $token->getUser();
        if ($user instanceof AdminUserInterface) {
            return $user;
        }

        return null;
    }

    public function getAccessCompany(): ?CompanyInterface
    {
        $company = $this->getUser()->getCompany();

        if($company instanceof CompanyInterface) {
            return $company;
        }

        return null;
    }

    public function getAccessCompaniesIds(): array
    {
        $companies = $this->getUser()->getCompanies();
        $ids = [];

        foreach($companies as $company) {
            $ids[] = $company->getId();
        }

        return $ids;
    }

    public function getRoleCanonicalName(): ?string
    {
        $setting = $this->getRoleSetting();

        if ($setting) {
            return $setting->getCanonicalName();
        }

        return null;
    }

    public function getTheme(): ?string
    {
        $setting = $this->getRoleSetting();

        if ($setting) {
            return $setting->getTheme();
        }

        return null;
    }

    private function getRoleSetting(): ?RoleSettingInterface
    {
        $user = $this->getUser();

        if (!$user instanceof RoleAwareInterface) {
            return null;
        }

        $role = $user->getRole();
        if ($role instanceof RoleInterface) {
            return $role->getSetting();
        }

        return null;
    }

    public function isAdminSystem(): bool
    {
        return $this->getRoleCanonicalName() === RoleAwareInterface::ROLE_ADMIN_SYSTEM_NAME;
    }

    public function isAdminCompany(): bool
    {
        return $this->getRoleCanonicalName() === RoleAwareInterface::ROLE_ADMIN_COMPANY_NAME;
    }

    public function isUser(): bool
    {
        return $this->getRoleCanonicalName() === RoleAwareInterface::ROLE_USER_NAME;
    }
}

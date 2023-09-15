<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Theme;

use Owl\Component\Core\Context\AdminUserContextInterface;
use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;

final class RoleBasedThemeContext implements ThemeContextInterface
{
    private AdminUserContextInterface $adminUserContext;

    private ThemeRepositoryInterface $themeRepository;

    public function __construct(AdminUserContextInterface $adminUserContext, ThemeRepositoryInterface $themeRepository)
    {
        $this->adminUserContext = $adminUserContext;
        $this->themeRepository = $themeRepository;
    }

    public function getTheme(): ?ThemeInterface
    {
        try {
            if ($this->adminUserContext->getUser()) {
                $themeName = $this->adminUserContext->getTheme();

                if ($themeName) {
                    return $this->themeRepository->findOneByName($themeName);
                }

                throw new ThemeNotFoundException();
            }

            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }
}

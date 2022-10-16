<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Theme;

use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Model\AdminUserInterface;
use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
            $themeName = $this->adminUserContext->getTheme();

            if ($themeName) {
                return $this->themeRepository->findOneByName($themeName);
            }

            throw new ThemeNotFoundException();
        } catch (\Exception $exception) {
            return null;
        }
    }
}

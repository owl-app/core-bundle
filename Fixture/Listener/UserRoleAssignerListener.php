<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Listener;

use Owl\Bundle\CoreBundle\Fixture\AdminUserFixture;
use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Repository\AdminUserRepositoryInterface;
use Owl\Component\Core\Updater\SingleRoleUpdaterInterface;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\AfterFixtureListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\FixtureEvent;

final class UserRoleAssignerListener extends AbstractListener implements AfterFixtureListenerInterface
{
    public function __construct(
        private SingleRoleUpdaterInterface $singleRoleUpdater,
        private AdminUserRepositoryInterface $adminUserRepository
    ) {
    }

    public function afterFixture(FixtureEvent $fixtureEvent, array $options): void
    {
        if (!$fixtureEvent->fixture() instanceof AdminUserFixture) {
            return;
        }

        $adminUsers = $this->adminUserRepository->findAll();

        /** @var AdminUserInterface $adminUser */
        foreach ($adminUsers as $adminUser) {
            $this->singleRoleUpdater->assign($adminUser);
        }
    }

    /**
     * @return string
     *
     * @psalm-return 'user_role_assigner'
     */
    public function getName(): string
    {
        return 'user_role_assigner';
    }
}

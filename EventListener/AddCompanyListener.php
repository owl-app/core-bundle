<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Owl\Component\Company\Model\CompanyAwareInterface;
use Owl\Component\Core\Context\AdminUserContextInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class AddCompanyListener
{
    private AdminUserContextInterface $adminUserContext;

    public function __construct(AdminUserContextInterface $adminUserContext)
    {
        $this->adminUserContext = $adminUserContext;
    }

    public function addCompany(GenericEvent $event): void
    {
        $subject = $event->getSubject();
        Assert::isInstanceOf($subject, CompanyAwareInterface::class);

        if ($this->adminUserContext->isAdminCompany() || $this->adminUserContext->isUser()) {
            $subject->setCompany($this->adminUserContext->getAccessCompany());
        }
    }
}

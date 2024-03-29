<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\EventSubscriber;

use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\Rbac\RoleInterface;
use Owl\Component\Core\Model\RoleAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

final class AddRoleSubscriber implements EventSubscriberInterface
{
    /**
     * @return string[]
     *
     * @psalm-return array{'form.submit': 'submit'}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'submit',
        ];
    }

    public function submit(FormEvent $event): void
    {
        /** @var AdminUserInterface $data */
        $data = $event->getData();
        $form = $event->getForm();

        Assert::isInstanceOf($data, RoleAwareInterface::class);

        $roles = $data->getRoles();
        $roleRbac = $data->getRole();

        Assert::isInstanceOf($roleRbac, RoleInterface::class);

        if ($roles) {
            foreach ($roles as $role) {
                $data->removeRole($role);
            }
        }

        $canonicalName = $roleRbac->getSetting()->getCanonicalName();
        $data->addRole($canonicalName);

        $event->setData($data);
    }
}

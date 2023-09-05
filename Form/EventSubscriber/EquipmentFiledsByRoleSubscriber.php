<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\EventSubscriber;

use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Model\Authorization\OwnerableUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class EquipmentFiledsByRoleSubscriber implements EventSubscriberInterface
{
    private AdminUserContextInterface $adminUserContext;

    public function __construct(AdminUserContextInterface $adminUserContext)
    {
        $this->adminUserContext = $adminUserContext;
    }

    /**
     * @return string[]
     *
     * @psalm-return array{'form.pre_set_data': 'preSetData'}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();
        $isUser = $this->adminUserContext->isUser();

        if ($data instanceof OwnerableUserInterface && ($isUser)) {
            $form->remove('symbol');
            $form->remove('unit');
            $form->remove('price');
            $form->remove('other');
        }
    }
}

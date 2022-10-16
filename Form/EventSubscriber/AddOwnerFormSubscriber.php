<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\EventSubscriber;

use Owl\Bundle\CoreBundle\Form\Type\CompanyChoiceType;
use Owl\Bundle\CoreBundle\Form\Type\UserChoiceType;
use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Model\Authorization\OwnerableUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AddOwnerFormSubscriber implements EventSubscriberInterface
{
    private AdminUserContextInterface $adminUserContext;

    public function __construct(AdminUserContextInterface $adminUserContext)
    {
        $this->adminUserContext = $adminUserContext;
    }

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
        $isAdminSystem = $this->adminUserContext->isAdminSystem();
        $isAdminCompany = $this->adminUserContext->isAdminCompany();

        if ($data instanceof OwnerableUserInterface && ($isAdminSystem || $isAdminCompany)) {
            $form
                ->add('user', UserChoiceType::class, [
                    'label' => 'owl.form.common.assign_user',
                ]);
        }

        if ($isAdminSystem) {
            $form->add('company', CompanyChoiceType::class, [
                'label' => 'owl.form.common.assign_company',
            ]);
        }
    }
}

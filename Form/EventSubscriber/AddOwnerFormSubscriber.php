<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\EventSubscriber;

use Owl\Bridge\SyliusResource\Doctrine\Orm\CollectionProviderInterface;
use Owl\Bundle\CoreBundle\Form\Type\CompanyChoiceType;
use Owl\Bundle\CoreBundle\Form\Type\UserChoiceType;
use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\Authorization\ManyOwnerableCompanyInterface;
use Owl\Component\Core\Model\Authorization\OwnerableCompanyInterface;
use Owl\Component\Core\Model\Authorization\OwnerableUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AddOwnerFormSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AdminUserContextInterface $adminUserContext,
        private RepositoryInterface $userRepository,
        private CollectionProviderInterface $collectionProvider
    ) {
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
        $showFiledUser = $form->getConfig()->getOptions()['show_field_user'] ?? true;
        $data = $event->getData();
        $isAdminSystem = $this->adminUserContext->isAdminSystem();
        $isAdminCompany = $this->adminUserContext->isAdminCompany();

        if ($data instanceof OwnerableUserInterface && ($isAdminSystem || $isAdminCompany)) {
            $form
                ->add('user', UserChoiceType::class, [
                    'choices' => $this->collectionProvider->get(
                        $this->userRepository, 
                        [],
                        [
                            'method' => 'findEnabledWithOwner',
                            'arguments' => [
                                'userId' => $data->getUser() ? $data->getUser()->getId() : null
                            ]
                        ]
                    ),
                    'label' => 'owl.form.common.assign_user',
                ]);
        }

        if ($data instanceof OwnerableCompanyInterface && ($isAdminSystem || $isAdminCompany)) {
            $form->add('company', CompanyChoiceType::class, [
                'label' => 'owl.form.common.assign_company'
            ]);
        }

        if ($data instanceof ManyOwnerableCompanyInterface && ($isAdminSystem || $isAdminCompany)) {
            $form->add('companies', CompanyChoiceType::class, [
                'label' => 'owl.form.common.assign_companies',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'row_attr' => ['style' => 'display: none'],
            ]);
        }
    }
}

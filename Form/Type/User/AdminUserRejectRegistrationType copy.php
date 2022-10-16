<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\User;

use Owl\Bundle\CoreBundle\Form\EventSubscriber\AddRoleSubscriber;
use Owl\Bundle\CoreBundle\Form\Type\CompanyChoiceType;
use Owl\Bundle\CoreBundle\Form\Type\RoleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminUserAcceptRegistrationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('company', CompanyChoiceType::class, [
                'label' => 'owl.form.common.assign_company',
            ])
            ->add('role', RoleChoiceType::class, [
                'label' => 'owl.form.user.role',
            ])
            ->addEventSubscriber(new AddRoleSubscriber());
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_admin_user_confirm_registration';
    }
}

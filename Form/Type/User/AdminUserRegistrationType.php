<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\User;

use Owl\Bundle\CoreBundle\Form\EventSubscriber\AdminUserRegistrationFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

final class AdminUserRegistrationType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups = [],
        private EventSubscriberInterface $adminUserRegistrationFormsubscriber
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'owl.form.common.first_name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'owl.form.common.last_name',
            ])
            ->add('email', EmailType::class, [
                'label' => 'owl.form.common.email',
            ])
            ->add('phone', TextType::class, [
                'label' => 'owl.form.common.phone_number',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'sylius.form.user.password.label'],
                'second_options' => ['label' => 'sylius.form.user.password.confirmation'],
                'invalid_message' => 'sylius.user.plainPassword.mismatch',
            ])
            ->add('registration', AdminUserRegistrationDataType::class, [
                'label' => false,
                'constraints' => [new Valid()],
            ])
            ->addEventSubscriber($this->adminUserRegistrationFormsubscriber)
            ->setDataLocked(false);
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_admin_user_registration';
    }
}

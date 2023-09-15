<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\User;

use Owl\Bundle\CoreBundle\Form\EventSubscriber\AddRoleSubscriber;
use Owl\Bundle\CoreBundle\Form\Type\RoleChoiceType;
use Owl\Bundle\UserBundle\Form\Type\UserType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminUserType extends UserType
{
    private ?string $fallbackLocale;

    private EventSubscriberInterface $addOwnerFormSubscriber;

    public function __construct(
        string $dataClass,
        array $validationGroups = [],
        ?string $fallbackLocale = null,
        EventSubscriberInterface $addOwnerFormSubscriber,
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->fallbackLocale = $fallbackLocale;
        $this->addOwnerFormSubscriber = $addOwnerFormSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('displayName', TextType::class, [
                'required' => true,
                'label' => 'owl.form.user.display_name',
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.phone',
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.first_name',
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.last_name',
            ])
            ->add('note', TextareaType::class, [
                'required' => false,
                'label' => 'owl.form.user.note',
            ])
            ->add('role', RoleChoiceType::class, [
                'label' => 'owl.form.user.role',
            ])
            ->add('localeCode', LocaleType::class, $this->provideLocaleCodeOptions())
        ;

        $builder->addEventSubscriber(new AddRoleSubscriber());
        $builder->addEventSubscriber($this->addOwnerFormSubscriber);
    }

    /**
     * @psalm-return 'sylius_admin_user'
     */
    public function getBlockPrefix(): string
    {
        return 'sylius_admin_user';
    }

    /**
     * @return (string|string[]|null)[]
     *
     * @psalm-return array{label: 'owl.ui.locale', placeholder: null, preferred_choices?: list{string}}
     */
    private function provideLocaleCodeOptions(): array
    {
        $localeCodeOptions = [
            'label' => 'owl.ui.locale',
            'placeholder' => null,
        ];

        if ($this->fallbackLocale !== null) {
            $localeCodeOptions['preferred_choices'] = [$this->fallbackLocale];
        }

        return $localeCodeOptions;
    }
}

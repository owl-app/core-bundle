<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Owl\Component\Core\Model\NotificationInterface;
use Owl\Component\Core\Repository\RoleRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GroupNotificationChoiceType extends AbstractType
{
    public function __construct(private RoleRepositoryInterface $roleRepository)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->getOptions(),
            'label' => false,
            'placeholder' => false,
        ]);
    }

    /**
     * @psalm-return ChoiceType::class
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @psalm-return 'owl_group_notification_choice'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_group_notification_choice';
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array<string, 'ALL'|mixed>
     */
    private function getOptions(): array
    {
        $groups = ['owl.ui.to_all' => NotificationInterface::GROUP_ASSIGNED_ALL];
        $roles = $this->roleRepository->findWithoutAdminSystem();

        if ($roles) {
            foreach ($roles as $role) {
                $name = $role->getSetting()->getCanonicalName();
                $groups['owl.ui.notification_group_assigned_' . strtolower($name)] = $name;
            }
        }

        return $groups;
    }
}

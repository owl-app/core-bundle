<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Extension;

use Owl\Bundle\CoreBundle\Form\Type\GroupNotificationChoiceType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Owl\Bundle\NotificationBundle\Form\Type\NotificationType;

final class NotificationTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('assignedGroup', GroupNotificationChoiceType::class, [
                'label' => 'owl.form.notification.group_assigned',
            ])
        ;
    }

    /**
     * @psalm-return NotificationType::class
     */
    public function getExtendedType(): string
    {
        return NotificationType::class;
    }

    /**
     * @return string[]
     *
     * @psalm-return list{NotificationType::class}
     */
    public static function getExtendedTypes(): iterable
    {
        return [NotificationType::class];
    }
}

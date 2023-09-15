<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Extension;

use Owl\Bundle\CoreBundle\Form\Type\EquipmentCategoryChoiceType;
use Owl\Bundle\EquipmentBundle\Form\Type\EquipmentAttributeValueType;
use Owl\Bundle\EquipmentBundle\Form\Type\EquipmentType;
use Owl\Component\Core\Resolver\RoleBasedValidationGroupResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EquipmentTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private EventSubscriberInterface $addOwnerFormSubscriber,
        private EventSubscriberInterface $equipmentFieldsByRole,
        private RoleBasedValidationGroupResolverInterface $validationGroupsResolver,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('category', EquipmentCategoryChoiceType::class, [
                'label' => 'owl.form.equipment.category',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('attributes', CollectionType::class, [
                'entry_type' => EquipmentAttributeValueType::class,
                'entry_options' => [
                    'attribute_choice_type_options' => [
                        'category' => $data['category'],
                    ],
                ],
                'required' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ]);
        });

        $builder->addEventSubscriber($this->addOwnerFormSubscriber);
        $builder->addEventSubscriber($this->equipmentFieldsByRole);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => $this->validationGroupsResolver,
        ]);
    }

    /**
     * @psalm-return EquipmentType::class
     */
    public function getExtendedType(): string
    {
        return EquipmentType::class;
    }

    /**
     * @return string[]
     *
     * @psalm-return list{EquipmentType::class}
     */
    public static function getExtendedTypes(): iterable
    {
        return [EquipmentType::class];
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Owl\Component\Core\Model\EquipmentAddOnInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EquipmentAddOnChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                EquipmentAddOnInterface::ADDON_REFUELING_NAME => EquipmentAddOnInterface::ADDON_REFUELING_CODE,
                EquipmentAddOnInterface::ADDON_EVENT_NAME => EquipmentAddOnInterface::ADDON_EVENT_CODE
            ],
            'choice_translation_domain' => true,
        ]);
    }

    /**
     * @return string
     *
     * @psalm-return ChoiceType::class
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @return string
     *
     * @psalm-return 'owl_equipment_addon_choice'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_equipment_addon_choice';
    }
}

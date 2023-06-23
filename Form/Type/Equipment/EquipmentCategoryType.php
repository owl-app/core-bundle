<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Symfony\Component\Form\FormBuilderInterface;
use Owl\Bundle\CategoryBundle\Form\Type\CategoryType;

final class EquipmentCategoryType extends CategoryType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('addons', EquipmentAddOnChoiceType::class, [
                'label' => 'owl.form.equipment.addon',
                'multiple' => true,
                'expanded' => false,
                'required' => true,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_equipment_category';
    }
}

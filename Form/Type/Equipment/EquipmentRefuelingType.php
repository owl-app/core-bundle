<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EquipmentRefuelingType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'owl.form.common.date',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('mileage', IntegerType::class, [
                'label' => 'owl.form.equipment_refueling.mileage',
                'required' => true,
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'owl.form.equipment_refueling.quantity',
                'required' => true,
            ])
            ->add('value', MoneyType::class, [
                'required' => false,
                'label' => 'owl.form.equipment_refueling.value',
                'currency' => $options['currency'] ?? null
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'owl.form.common.description',
                'attr' => ['rows' => 2],
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_equipment_event';
    }
}

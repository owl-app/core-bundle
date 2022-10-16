<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Equipment;

use Owl\Component\Core\Model\EquipmentEventInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

class EquipmentEventType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'owl.form.common.date',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('dateNotify', DateType::class, [
                'label' => 'owl.form.equipment_event.date_notify',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'owl.form.common.description',
                'attr' => ['rows' => 3],
                'required' => true,
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                $equipmentEvent = $form->getData();

                if (strtotime($data['dateNotify']) != ($equipmentEvent->getDateNotify())?->getTimestamp()) {
                    $equipmentEvent->setNotifyState(empty($data['dateNotify']) ? null : EquipmentEventInterface::NOTIFY_STATE_WAITING);
                    $form->setData($equipmentEvent);
                }
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_equipment_event';
    }
}

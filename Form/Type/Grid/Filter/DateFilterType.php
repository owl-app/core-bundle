<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Grid\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', DateType::class, [
                'label' => 'sylius.ui.from',
                'widget' => 'single_text',
                'placeholder' => ['year' => '-', 'month' => '-', 'day' => '-'],
                'required' => false,
            ])
            ->add('to', DateType::class, [
                'label' => 'sylius.ui.to',
                'widget' => 'single_text',
                'placeholder' => ['year' => '-', 'month' => '-', 'day' => '-'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
            ])
        ;
    }

    /**
     * @psalm-return 'owl_grid_filter_date'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_grid_filter_date';
    }
}

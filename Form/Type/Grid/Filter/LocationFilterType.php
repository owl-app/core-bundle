<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Grid\Filter;

use Owl\Bundle\CityBundle\Form\Type\CityAutocompleteChoiceType;
use Sylius\Component\Grid\Filter\StringFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LocationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('city', CityAutocompleteChoiceType::class, [
                'label' => 'owl.form.common.city',
                'multiple' => false,
                'required' => false,
                'choice_value' => 'id',
                'from_text_transformer' => true
            ])
            ->add('distance', TextType::class, [
                'required' => false,
                'label' => 'sylius.ui.distance',
            ])
        ;

        if($options['extra_fields']) {
            foreach($options['extra_fields'] as $extraField) {
                $builder
                    ->add($extraField, HiddenType::class, [
                        'required' => false,
                        'label' => 'sylius.ui.value',
                    ])
                ;
            }
        } else {
            $builder
                ->add('value', TextType::class, [
                    'required' => false,
                    'label' => 'sylius.ui.value',
                ])
            ;
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['extra_fields'] = $options['extra_fields'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'allow_extra_fields' => true,
                'extra_fields' => ['latitude', 'longitude']
            ])
            ->setDefined('type')
            ->setAllowedValues('type', [
                StringFilter::TYPE_CONTAINS,
                StringFilter::TYPE_NOT_CONTAINS,
                StringFilter::TYPE_EQUAL,
                StringFilter::TYPE_NOT_EQUAL,
                StringFilter::TYPE_EMPTY,
                StringFilter::TYPE_NOT_EMPTY,
                StringFilter::TYPE_STARTS_WITH,
                StringFilter::TYPE_ENDS_WITH,
                StringFilter::TYPE_IN,
                StringFilter::TYPE_NOT_IN,
            ])
            ->setAllowedTypes('extra_fields', ['array'])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_grid_filter_location';
    }
}

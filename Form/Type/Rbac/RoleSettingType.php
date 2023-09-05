<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\Rbac;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ThemeBundle\Form\Type\ThemeNameChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RoleSettingType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('canonicalName', TextType::class, [
                'label' => 'owl.form.role.canonical_name',
                'empty_data' => null,
                'disabled' => $options['disabled_name']
            ])
            ->add('theme', ThemeNameChoiceType::class, [
                'label' => 'owl.form.role.choice_theme',
                'empty_data' => null,
                'placeholder' => 'owl.ui.no_theme',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'disabled_name' => false,
        ]);

        $resolver->setAllowedTypes('disabled_name', 'bool');
    }

    /**
     * @return string
     *
     * @psalm-return 'owl_rbac_role_setting'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_rbac_role_setting';
    }
}

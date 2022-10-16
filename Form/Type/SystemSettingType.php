<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SystemSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description_dashboard', TextareaType::class, [
                'label' => 'owl.form.setting.dashboard_description',
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'advanced'
                ),
                'constraints' => [
                    new NotBlank()
                ],
                'required' => true
            ])
            ->add('description_login_page', TextareaType::class, [
                'label' => 'owl.form.setting.login_page_description',
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'advanced'
                ),
                'constraints' => [
                    new NotBlank()
                ],
                'required' => true
            ])
        ;
    }
}

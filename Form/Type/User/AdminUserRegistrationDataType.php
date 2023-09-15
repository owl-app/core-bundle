<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\User;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminUserRegistrationDataType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('company', TextType::class, [
                'label' => 'owl.form.registration.company',
            ])
            ->add('nip', TextType::class, [
                'label' => 'owl.form.common.nip',
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.city',
            ])
            ->add('street', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.street',
            ])
            ->add('postCode', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.post_code',
            ])
        ;
    }

    /**
     * @psalm-return 'owl_admin_user_registration_data'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_admin_user_registration_data';
    }
}

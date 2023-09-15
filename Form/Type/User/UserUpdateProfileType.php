<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type\User;

use Owl\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;

final class UserUpdateProfileType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        protected RepositoryInterface $localeRepository,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.first_name',
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.last_name',
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'owl.form.common.phone_number',
            ])
            ->add('localeCode', LocaleChoiceType::class, [
                'required' => false,
                'label' => 'owl.ui.locale',
                'placeholder' => null,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'sylius.form.user.password.label'],
                'second_options' => ['label' => 'sylius.form.user.password.confirmation'],
                'invalid_message' => 'sylius.user.plainPassword.mismatch',
            ])
        ;

        $builder->get('localeCode')->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->localeRepository, 'code')),
        );
    }

    /**
     * @psalm-return 'owl_user_update_profile'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_user_update_profile';
    }
}

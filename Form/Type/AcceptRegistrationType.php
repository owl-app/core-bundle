<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Owl\Bundle\CoreBundle\Form\Type\User\AdminUserAcceptRegistrationType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

final class AcceptRegistrationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('user', AdminUserAcceptRegistrationType::class, [
                'label' => false,
                'constraints' => [new Valid()],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_accept_registration';
    }
}


<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

final class RejectRegistrationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('reasonRejection', TextareaType::class, [
                'label' => 'owl.form.registration.reason_rejection',
            ])
        ;
    }

    /**
     * @psalm-return 'owl_reject_registration'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_reject_registration';
    }
}

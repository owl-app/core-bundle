<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Owl\Bridge\SyliusResource\Doctrine\Orm\CollectionProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserChoiceType extends AbstractType
{
    public function __construct(
        private EntityRepository $userRepository,
        private CollectionProviderInterface $collectionProvider,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->collectionProvider->get($this->userRepository),
            'choice_value' => 'id',
            'choice_label' => 'displayName',
            'label' => false,
            'empty_data' => null,
            'placeholder' => 'owl.form.user.no_user',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_company_choice';
    }
}

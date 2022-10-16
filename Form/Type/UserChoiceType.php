<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Owl\Component\Core\Authorization\Owner\OwnerConditionProviderInterface;
use Owl\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserChoiceType extends AbstractType
{
    private RepositoryInterface $userRepository;

    private OwnerConditionProviderInterface $ownerConditionProvider;

    public function __construct(RepositoryInterface $userRepository, OwnerConditionProviderInterface $ownerConditionProvider)
    {
        $this->userRepository = $userRepository;
        $this->ownerConditionProvider = $ownerConditionProvider;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->getUsers(),
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

    private function getUsers(): array
    {
        $criteria = [];
        $ownerCondition = $this->ownerConditionProvider->provide($this->userRepository->getClassName());

        if ($ownerCondition) {
            $criteria = array_merge($criteria, $ownerCondition->getCriteria());
        }
        return $this->userRepository->findBy($criteria);
    }
}

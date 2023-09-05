<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Owl\Bridge\SyliusResource\Doctrine\Orm\CollectionProviderInterface;
use Owl\Component\Core\Authorization\Owner\OwnerConditionProviderInterface;
use Owl\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserChoiceType extends AbstractType
{

    public function __construct(
        private RepositoryInterface $userRepository,
        private CollectionProviderInterface $collectionProvider
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

    /**
     * @return string
     *
     * @psalm-return ChoiceType::class
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @return string
     *
     * @psalm-return 'owl_company_choice'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_company_choice';
    }

    /**
     * @return \Sylius\Component\Resource\Model\ResourceInterface[]
     *
     * @psalm-return array<T>
     */
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

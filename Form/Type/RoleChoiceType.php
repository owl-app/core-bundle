<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Owl\Component\Core\Context\AdminUserContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RoleChoiceType extends AbstractType
{
    private RepositoryInterface $roleRepository;

    private AdminUserContextInterface $adminUserContext;

    public function __construct(RepositoryInterface $roleRepository, AdminUserContextInterface $adminUserContext)
    {
        $this->roleRepository = $roleRepository;
        $this->adminUserContext = $adminUserContext;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_reverse($this->getOptions()),
            'choice_value' => 'id',
            'choice_label' => 'name',
            'label' => false,
            'placeholder' => false,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'owl_role_choice';
    }

    private function getOptions(): array
    {
        if (!$this->adminUserContext->isAdminSystem()) {
            return $this->roleRepository->findWithoutAdminSystem();
        }

        return $this->roleRepository->findAll();
    }
}

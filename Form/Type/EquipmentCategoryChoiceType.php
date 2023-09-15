<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EquipmentCategoryChoiceType extends AbstractType
{
    private RepositoryInterface $equipmentCategoryRepository;

    public function __construct(RepositoryInterface $equipmentCategoryRepository)
    {
        $this->equipmentCategoryRepository = $equipmentCategoryRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => /**
             * @return \Sylius\Component\Resource\Model\ResourceInterface[]
             *
             * @psalm-return array<T>
             */
            function (Options $options): array {
                return $this->equipmentCategoryRepository->findAll();
            },
            'choice_value' => 'id',
            'choice_label' => 'name',
            'label' => false,
            'empty_data' => null,
            'placeholder' => 'owl.form.common.no_category',
        ]);
    }

    /**
     * @psalm-return ChoiceType::class
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @psalm-return 'owl_equipment_category_choice'
     */
    public function getBlockPrefix(): string
    {
        return 'owl_equipment_category_choice';
    }
}

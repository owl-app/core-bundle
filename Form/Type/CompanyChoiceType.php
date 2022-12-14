<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CompanyChoiceType extends AbstractType
{
    private RepositoryInterface $companyRepository;

    public function __construct(RepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => function (Options $options): array {
                return $this->companyRepository->findAll();
            },
            'choice_value' => 'id',
            'choice_label' => 'name',
            'label' => false,
            'empty_data' => null,
            'placeholder' => 'owl.form.user.no_company',
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

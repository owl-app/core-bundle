<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Owl\Bridge\SyliusResource\Doctrine\Orm\CollectionProviderInterface;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CompanyChoiceType extends AbstractType
{
    public function __construct(private EntityRepository $companyRepository, private CollectionProviderInterface $collectionProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->collectionProvider->get($this->companyRepository),
            'choice_value' => 'id',
            'choice_label' => 'name',
            'label' => false,
            'empty_data' => [],
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

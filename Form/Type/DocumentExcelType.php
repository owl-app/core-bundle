<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class DocumentExcelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'owl.ui.old_version_excel' => 'xls',
                    'owl.ui.new_version_excel' => 'xlsx',
                ],
                'label' => 'owl.ui.please_chose_your_version_excel',
                'empty_data' => null,
            ])
        ;
    }
}

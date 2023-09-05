<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\Extension;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class MoneyTypeExtension extends AbstractTypeExtension
{
    private string $defaultCurrency;

    public function __construct(string $defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['currency'] = $options['currency'] ?? $this->defaultCurrency;
    }

    /**
     * @return string[]
     *
     * @psalm-return list{MoneyType::class}
     */
    public static function getExtendedTypes(): array
    {
        return [MoneyType::class];
    }
}

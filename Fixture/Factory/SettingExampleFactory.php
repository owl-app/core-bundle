<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Faker\Factory;
use Faker\Generator;
use Owl\Component\Setting\Model\SettingInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingExampleFactory extends AbstractExampleFactory
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $settingFactory,
        private string $localeCode,
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined('section')
            ->setDefined('name')
            ->setDefault('value', fn (Options $options): string => (string) $this->faker->words(3, true))
            ->setDefault('lang', $this->localeCode)
        ;
    }

    public function create(array $options = []): SettingInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var SettingInterface $setting */
        $setting = $this->settingFactory->createNew();
        $setting->setSection($options['section']);
        $setting->setName($options['name']);
        $setting->setValue($options['value']);
        $setting->setLang($options['lang']);

        return $setting;
    }
}

<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Faker\Generator;
use Faker\Factory;
use Owl\Component\Core\Model\CompanyInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyExampleFactory extends AbstractExampleFactory
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $companyFactory
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                return $this->faker->firstName;
            })
            ->setDefault('nip', function (Options $options): string {
                return $this->faker->nip;
            })
            ->setDefault('city', function (Options $options): string {
                return $this->faker->city;
            })
            ->setDefault('street', function (Options $options): string {
                return $this->faker->streetAddress;
            })
            ->setDefault('post_code', function (Options $options): string {
                return $this->faker->postcode;
            })
            ->setDefault('phone', function (Options $options): ?string {
                return $this->faker->phoneNumber;
            })
            ->setDefault('email', function (Options $options): ?string {
                return $this->faker->email;
            })
        ;
    }

    public function create(array $options = []): CompanyInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var CompanyInterface $company */
        $company = $this->companyFactory->createNew();
        $company->setName($options['name']);
        $company->setNip($options['nip']);
        $company->setCity($options['city']);
        $company->setStreet($options['street']);
        $company->setPostCode($options['post_code']);
        $company->setPhone($options['phone']);
        $company->setEmail($options['email']);

        return $company;
    }
}

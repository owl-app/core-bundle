<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Faker\Generator;
use Faker\Factory;
use Doctrine\Common\Collections\Collection;
use Owl\Component\Core\Model\CompanyInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ProvinceInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

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

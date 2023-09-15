<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Faker\Factory;
use Faker\Generator;
use Owl\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $userFactory,
        private ReferenceRepository $companyReference,
        private ReferenceRepository $roleReference,
        private string $localeCode,
    ) {
        $this->userFactory = $userFactory;
        $this->localeCode = $localeCode;

        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): AdminUserInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var AdminUserInterface $user */
        $user = $this->userFactory->createNew();
        $user->setDisplayName($options['display_name']);
        $user->setFirstName($options['first_name']);
        $user->setLastName($options['last_name']);
        $user->setPhone($options['phone']);
        $user->setEmail($options['email']);
        $user->setPlainPassword($options['password']);
        $user->setEnabled($options['enabled']);
        $user->addRole($options['role']);
        $user->setLocaleCode($options['locale_code']);

        if (isset($options['company_reference']) && $this->companyReference->hasReference($options['company_reference'])) {
            $user->setCompany($this->companyReference->getReference($options['company_reference']));
        }

        if (isset($options['role_reference']) && $this->roleReference->hasReference($options['role_reference'])) {
            $user->setRole($this->roleReference->getReference($options['role_reference']));
        }

        return $user;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined('role_reference')
            ->setDefined('company_reference')
            ->setDefault('display_name', function (Options $options): string {
                return $this->faker->firstName . ' ' . $this->faker->lastName;
            })
            ->setDefault('first_name', function (Options $options): string {
                return $this->faker->firstName;
            })
            ->setDefault('last_name', function (Options $options): string {
                return $this->faker->lastName;
            })
            ->setDefault('phone', function (Options $options): string {
                return $this->faker->phoneNumber;
            })
            ->setDefault('email', function (Options $options): string {
                return $this->faker->email;
            })
            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', 'bool')
            ->setDefault('password', 'password123')
            ->setDefault('locale_code', $this->localeCode)
            ->setDefined('role')
        ;
    }
}

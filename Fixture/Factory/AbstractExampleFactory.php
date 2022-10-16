<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Fixture\Factory;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractExampleFactory implements ExampleFactoryInterface
{
    abstract protected function configureOptions(OptionsResolver $resolver): void;
}

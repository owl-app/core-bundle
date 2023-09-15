<?php

declare(strict_types=1);

namespace spec\Owl\Bundle\CoreBundle\Grid;

use Owl\Component\Core\Provider\SuffixGridProviderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\Provider\GridProviderInterface;

final class SuffixableGridProviderSpec extends ObjectBehavior
{
    function let(
        GridProviderInterface $decoratedGridProvider,
        SuffixGridProviderInterface $suffixGridProvider,
    ): void {
        $this->beConstructedWith($decoratedGridProvider, $suffixGridProvider, ['grid' => [], 'grid_suffix' => []]);
    }

    function it_implements_suffix_grid_provider_interface(): void
    {
        $this->shouldImplement(GridProviderInterface::class);
    }

    function it_get_grid_with_existing_configuration_by_suffix(
        GridProviderInterface $decoratedGridProvider,
        SuffixGridProviderInterface $suffixGridProvider,
    ): void {
        $suffixGridProvider->getSuffix()->willReturn('_suffix');

        $decoratedGridProvider->get('grid_suffix')->shouldBeCalled();

        $this->get('grid');
    }

    function it_get_grid_with_non_existing_configuration_by_suffix(
        GridProviderInterface $decoratedGridProvider,
        SuffixGridProviderInterface $suffixGridProvider,
    ): void {
        $suffixGridProvider->getSuffix()->willReturn('_bad_sufix');

        $decoratedGridProvider->get('grid')->shouldBeCalled();

        $this->get('grid');
    }
}

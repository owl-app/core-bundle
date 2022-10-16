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

namespace Owl\Bundle\CoreBundle\Grid;

use Owl\Component\Core\Provider\SuffixGridProviderInterface;
use Sylius\Component\Grid\Definition\ArrayToDefinitionConverterInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Exception\UndefinedGridException;
use Sylius\Component\Grid\Provider\GridProviderInterface;

final class SuffixableGridProvider implements GridProviderInterface
{
    private GridProviderInterface $decoratedGridProvider;

    private SuffixGridProviderInterface $suffixGridProvider;

    /** @var array[] */
    private array $gridConfigurations;

    public function __construct(
        GridProviderInterface $decoratedGridProvider,
        SuffixGridProviderInterface $suffixGridProvider,
        $gridConfigurations
    ) {
        $this->decoratedGridProvider = $decoratedGridProvider;
        $this->suffixGridProvider = $suffixGridProvider;
        $this->gridConfigurations = $gridConfigurations;
    }

    public function get(string $code): Grid
    {
        $suffixedCode = $code.$this->suffixGridProvider->getSuffix();

        if (array_key_exists($suffixedCode, $this->gridConfigurations)) {
            $code = $suffixedCode;
        }

        return $this->decoratedGridProvider->get($code);
    }
}

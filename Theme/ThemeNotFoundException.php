<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Theme;

final class ThemeNotFoundException extends \RuntimeException
{
    public function __construct(?string $message = null, \Exception $previousException = null)
    {
        parent::__construct($message ?: 'Theme could not be found!', 0, $previousException);
    }

    public static function notFound(string $theme): self
    {
        return new self(sprintf('Theme "%s" cannot be found!', $theme));
    }
}

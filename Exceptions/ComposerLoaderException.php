<?php

namespace Modules\Appearance\Exceptions;

use RuntimeException;

class ComposerLoaderException extends RuntimeException
{
    /**
     * @return \Modules\Appearance\Exceptions\ComposerLoaderException
     */
    public static function duplicate(string $name): self
    {
        return new static(sprintf(
            'A package named "%s" already exists.',
            $name
        ));
    }
}

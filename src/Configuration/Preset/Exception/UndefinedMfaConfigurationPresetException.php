<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration\Preset\Exception;

use Exception;

/**
 * An undefined multi-factor authentication configuration preset was requested.
 */
final class UndefinedMfaConfigurationPresetException extends Exception
{
    /**
     * Construct a new undefined multi-factor authentication configuration preset exception.
     *
     * @param string         $key      The requested preset key.
     * @param Exception|null $previous The cause, if available.
     */
    public function __construct($key, Exception $previous = null)
    {
        $this->key = $key;

        parent::__construct(
            sprintf(
                'Undefined multi-factor authentication preset %s.',
                var_export($key, true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the requested preset key.
     *
     * @return string The key.
     */
    public function key()
    {
        return $this->key;
    }

    private $key;
}

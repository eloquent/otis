<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Exception;

use Exception;

/**
 * An invalid length HOTP result was supplied.
 */
class InvalidResultLengthException extends Exception
{
    /**
     * Construct a new invalid result length exception.
     *
     * @param integer        $length   The length of the supplied result.
     * @param Exception|null $previous The cause, if available.
     */
    public function __construct($length, Exception $previous = null)
    {
        $this->length = $length;

        parent::__construct(
            sprintf(
                'Invalid HOTP result length (%s).',
                var_export($length, true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the length of the supplied result.
     *
     * @return integer The length of the result.
     */
    public function length()
    {
        return $this->length;
    }

    private $length;
}

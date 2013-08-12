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
 * The requested password length is invalid.
 */
class InvalidPasswordLengthException extends Exception
{
    /**
     * Construct a new invalid password length exception.
     *
     * @param integer        $length   The length requested.
     * @param Exception|null $previous The cause, if available.
     */
    public function __construct($length, Exception $previous = null)
    {
        $this->length = $length;

        parent::__construct(
            sprintf(
                'Invalid HOTP password length (%s).',
                var_export($length, true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the requested password length.
     *
     * @return integer The length requested.
     */
    public function length()
    {
        return $this->length;
    }

    private $length;
}

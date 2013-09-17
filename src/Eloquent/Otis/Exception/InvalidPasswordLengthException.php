<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Exception;

use Exception;

/**
 * The requested password length is invalid.
 */
class InvalidPasswordLengthException extends Exception
{
    /**
     * Construct a new invalid password length exception.
     *
     * @param integer        $digits   The number of digits requested.
     * @param Exception|null $previous The cause, if available.
     */
    public function __construct($digits, Exception $previous = null)
    {
        $this->digits = $digits;

        parent::__construct(
            sprintf(
                'Invalid password length (%s).',
                var_export($digits, true)
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
    public function digits()
    {
        return $this->digits;
    }

    private $digits;
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Result\Exception;

use Exception;

/**
 * The supplied OTP validation result arguments constitute an invalid result.
 */
class InvalidResultException extends Exception
{
    /**
     * Construct a new invalid result exception.
     *
     * @param Exception|null $previous The cause, if available.
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Invalid OTP validation result.', 0, $previous);
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Otp\Value;

use Eloquent\Otis\Exception\InvalidPasswordLengthException;

/**
 * The interface implemented by generated OTP values.
 */
interface OtpValueInterface
{
    /**
     * Get the raw value.
     *
     * @return mixed The raw value.
     */
    public function value();

    /**
     * Generate a string with a fixed number of characters from the result.
     *
     * @param integer|null $length The number of characters in the result string.
     *
     * @return string                         The result string.
     * @throws InvalidPasswordLengthException If the requested length is invalid.
     */
    public function string($digits = null);

    /**
     * Generate a string with the default number of characters from the result.
     *
     * @return string The result string.
     */
    public function __toString();
}

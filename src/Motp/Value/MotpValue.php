<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Value;

use Eloquent\Otis\Exception\InvalidPasswordLengthException;
use Eloquent\Otis\Otp\Value\OtpValueInterface;

/**
 * Represents a generated mOTP value.
 */
class MotpValue implements OtpValueInterface
{
    /**
     * Construct a new mOTP value.
     *
     * @param string $value The raw value.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the raw value.
     *
     * @return mixed The raw value.
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Generate a string with a fixed number of characters from the result.
     *
     * @param integer|null $length The number of characters in the result string.
     *
     * @return string                         The result string.
     * @throws InvalidPasswordLengthException If the requested length is invalid.
     */
    public function string($digits = null)
    {
        if (null === $digits) {
            $digits = 6;
        }
        if ($digits < 6 || $digits > 32) {
            throw new InvalidPasswordLengthException($digits);
        }

        return substr($this->value(), 0, $digits);
    }

    /**
     * Generate a string with the default number of characters from the result.
     *
     * @return string The result string.
     */
    public function __toString()
    {
        return $this->string(6);
    }

    private $value;
}

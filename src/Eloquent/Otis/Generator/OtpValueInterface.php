<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Generator;

use Eloquent\Otis\Configuration\Exception\InvalidPasswordLengthException;

/**
 * The interface implemented by generated OTP values.
 */
interface OtpValueInterface
{
    /**
     * Get the raw value.
     *
     * @return string The raw value.
     */
    public function value();

    /**
     * Get the truncated value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @return integer The truncated value.
     */
    public function truncated();

    /**
     * Generate a numeric string with a fixed number of digits from the result.
     *
     * @param integer|null $digits The number of digits in the result string.
     *
     * @return string                         The result string.
     * @throws InvalidPasswordLengthException If the requested length is invalid.
     */
    public function string($digits = null);

    /**
     * Generate a numeric string with 6 digits from the result.
     *
     * @return string The result string.
     */
    public function __toString();
}

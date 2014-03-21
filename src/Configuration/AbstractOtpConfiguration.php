<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

use Eloquent\Otis\Exception\InvalidPasswordLengthException;

/**
 * An abstract base class for implementing one-time password authentication
 * configurations.
 */
abstract class AbstractOtpConfiguration implements OtpConfigurationInterface
{
    /**
     * Construct a new one-time password authentication configuration.
     *
     * @param integer|null $digits       The number of password digits.
     * @param integer|null $secretLength The length of the shared secret.
     *
     * @throws InvalidPasswordLengthException If the number of digits is invalid.
     */
    public function __construct($digits = null, $secretLength = null)
    {
        if (null === $digits) {
            $digits = 6;
        }
        if (null === $secretLength) {
            $secretLength = 10;
        }

        if ($digits < 6) {
            throw new InvalidPasswordLengthException($digits);
        }

        $this->digits = $digits;
        $this->secretLength = $secretLength;
    }

    /**
     * Get the number of password digits.
     *
     * @return integer The number of digits.
     */
    public function digits()
    {
        return $this->digits;
    }

    /**
     * Get the length of the shared secret.
     *
     * @return integer The secret length.
     */
    public function secretLength()
    {
        return $this->secretLength;
    }

    private $digits;
    private $secretLength;
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Configuration;

use Eloquent\Otis\Exception\InvalidPasswordLengthException;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;

/**
 * An abstract base class for implementing one-time password authentication
 * configurations based upon HOTP.
 */
abstract class AbstractHotpBasedOtpConfiguration implements
    HotpBasedConfigurationInterface
{
    /**
     * Construct a new one-time password authentication configuration.
     *
     * @param integer|null           $digits       The number of password digits.
     * @param integer|null           $secretLength The length of the shared secret.
     * @param HotpHashAlgorithm|null $algorithm    The underlying hash algorithm to use.
     *
     * @throws InvalidPasswordLengthException If the number of digits is invalid.
     */
    public function __construct(
        $digits = null,
        $secretLength = null,
        HotpHashAlgorithm $algorithm = null
    ) {
        if (null === $digits) {
            $digits = 6;
        }
        if (null === $secretLength) {
            $secretLength = 10;
        }
        if (null === $algorithm) {
            $algorithm = HotpHashAlgorithm::SHA1();
        }

        if ($digits < 6 || $digits > 10) {
            throw new InvalidPasswordLengthException($digits);
        }

        $this->digits = $digits;
        $this->secretLength = $secretLength;
        $this->algorithm = $algorithm;
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

    /**
     * Get the underlying algorithm name.
     *
     * @return HotpHashAlgorithm The algorithm name.
     */
    public function algorithm()
    {
        return $this->algorithm;
    }

    private $digits;
    private $secretLength;
    private $algorithm;
}

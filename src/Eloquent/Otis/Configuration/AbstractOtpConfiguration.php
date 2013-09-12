<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

/**
 * An abstract base class for implementing OTP configurations.
 */
abstract class AbstractOtpConfiguration implements OtpConfigurationInterface
{
    /**
     * Construct a new OTP configuration.
     *
     * @param integer|null       $digits       The number of password digits.
     * @param integer|null       $secretLength The length of the shared secret.
     * @param HashAlgorithm|null $algorithm    The underlying algorithm to use.
     */
    public function __construct(
        $digits = null,
        $secretLength = null,
        HashAlgorithm $algorithm = null
    ) {
        if (null === $digits) {
            $digits = 6;
        }
        if (null === $secretLength) {
            $secretLength = 10;
        }
        if (null === $algorithm) {
            $algorithm = HashAlgorithm::SHA1();
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
     * @return HashAlgorithm The algorithm name.
     */
    public function algorithm()
    {
        return $this->algorithm;
    }

    private $digits;
    private $secretLength;
    private $algorithm;
}

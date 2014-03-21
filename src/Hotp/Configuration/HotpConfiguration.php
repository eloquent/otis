<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Configuration;

use Eloquent\Otis\Configuration\AbstractCounterBasedOtpConfiguration;
use Eloquent\Otis\Exception\InvalidPasswordLengthException;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;

/**
 * Represents a complete set of HOTP configuration settings.
 */
class HotpConfiguration extends AbstractCounterBasedOtpConfiguration implements
    HotpConfigurationInterface
{
    /**
     * Construct a new HOTP configuration.
     *
     * @param integer|null           $digits         The number of password digits.
     * @param integer|null           $window         The amount of counter increments to search through for a match.
     * @param integer|null           $initialCounter The initial counter value.
     * @param integer|null           $secretLength   The length of the shared secret.
     * @param HotpHashAlgorithm|null $algorithm      The underlying algorithm to use.
     *
     * @throws InvalidPasswordLengthException If the number of digits is invalid.
     */
    public function __construct(
        $digits = null,
        $window = null,
        $initialCounter = null,
        $secretLength = null,
        HotpHashAlgorithm $algorithm = null
    ) {
        if (null !== $digits && $digits > 10) {
            throw new InvalidPasswordLengthException($digits);
        }
        if (null === $algorithm) {
            $algorithm = HotpHashAlgorithm::SHA1();
        }

        parent::__construct($digits, $window, $initialCounter, $secretLength);

        $this->algorithm = $algorithm;
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

    private $algorithm;
}

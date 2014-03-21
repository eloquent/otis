<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Configuration;

use Eloquent\Otis\Configuration\AbstractTimeBasedOtpConfiguration;
use Eloquent\Otis\Exception\InvalidPasswordLengthException;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;

/**
 * Represents a complete set of TOTP configuration settings.
 */
class TotpConfiguration extends AbstractTimeBasedOtpConfiguration implements
    TotpConfigurationInterface
{
    /**
     * Construct a new TOTP configuration.
     *
     * @param integer|null           $digits        The number of password digits.
     * @param integer|null           $window        The number of seconds each token is valid for.
     * @param integer|null           $futureWindows The number of future windows to check.
     * @param integer|null           $pastWindows   The number of past windows to check.
     * @param integer|null           $secretLength  The length of the shared secret.
     * @param HotpHashAlgorithm|null $algorithm     The underlying algorithm to use.
     *
     * @throws InvalidPasswordLengthException If the number of digits is invalid.
     */
    public function __construct(
        $digits = null,
        $window = null,
        $futureWindows = null,
        $pastWindows = null,
        $secretLength = null,
        HotpHashAlgorithm $algorithm = null
    ) {
        if (null !== $digits && $digits > 10) {
            throw new InvalidPasswordLengthException($digits);
        }
        if (null === $algorithm) {
            $algorithm = HotpHashAlgorithm::SHA1();
        }

        parent::__construct(
            $digits,
            $window,
            $futureWindows,
            $pastWindows,
            $secretLength
        );

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

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
 * Represents a complete set of HOTP configuration settings.
 */
class HotpConfiguration extends AbstractOtpConfiguration implements
    HotpConfigurationInterface
{
    /**
     * Construct a new HOTP configuration.
     *
     * @param integer|null       $digits         The number of password digits.
     * @param integer|null       $window         The amount of counter increments to search through for a match.
     * @param integer|null       $initialCounter The initial counter value.
     * @param integer|null       $secretLength   The length of the shared secret.
     * @param HashAlgorithm|null $algorithm      The underlying algorithm to use.
     */
    public function __construct(
        $digits = null,
        $window = null,
        $initialCounter = null,
        $secretLength = null,
        HashAlgorithm $algorithm = null
    ) {
        if (null === $window) {
            $window = 10;
        }
        if (null === $initialCounter) {
            $initialCounter = 1;
        }

        parent::__construct($digits, $secretLength, $algorithm);

        $this->window = $window;
        $this->initialCounter = $initialCounter;
    }

    /**
     * Get the amount of counter increments to search through for a match.
     *
     * @return integer The amount of counter increments to search through for a match.
     */
    public function window()
    {
        return $this->window;
    }

    /**
     * Get the initial counter value.
     *
     * @return integer The initial counter value.
     */
    public function initialCounter()
    {
        return $this->initialCounter;
    }

    private $window;
    private $initialCounter;
}

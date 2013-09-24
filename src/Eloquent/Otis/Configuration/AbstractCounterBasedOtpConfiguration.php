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

use Eloquent\Otis\Exception\InvalidPasswordLengthException;

/**
 * An abstract base class for creating counter-based one-time password
 * authentication configurations.
 */
abstract class AbstractCounterBasedOtpConfiguration
    extends AbstractOtpConfiguration
    implements CounterBasedOtpConfigurationInterface
{
    /**
     * Construct a new counter-based one-time password authentication
     * configuration.
     *
     * @param integer|null $digits         The number of password digits.
     * @param integer|null $window         The amount of counter increments to search through for a match.
     * @param integer|null $initialCounter The initial counter value.
     * @param integer|null $secretLength   The length of the shared secret.
     *
     * @throws InvalidPasswordLengthException If the number of digits is invalid.
     */
    public function __construct(
        $digits = null,
        $window = null,
        $initialCounter = null,
        $secretLength = null
    ) {
        if (null === $window) {
            $window = 10;
        }
        if (null === $initialCounter) {
            $initialCounter = 1;
        }

        parent::__construct($digits, $secretLength);

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

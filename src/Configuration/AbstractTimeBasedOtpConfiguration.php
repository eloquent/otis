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
 * An abstract base class for creating time-based one-time password
 * authentication configurations.
 */
abstract class AbstractTimeBasedOtpConfiguration
    extends AbstractOtpConfiguration
    implements TimeBasedOtpConfigurationInterface
{
    /**
     * Construct a new time-based one-time password authentication
     * configuration.
     *
     * @param integer|null $digits        The number of password digits.
     * @param integer|null $window        The number of seconds each token is valid for.
     * @param integer|null $futureWindows The number of future windows to check.
     * @param integer|null $pastWindows   The number of past windows to check.
     * @param integer|null $secretLength  The length of the shared secret.
     *
     * @throws InvalidPasswordLengthException If the number of digits is invalid.
     */
    public function __construct(
        $digits = null,
        $window = null,
        $futureWindows = null,
        $pastWindows = null,
        $secretLength = null
    ) {
        if (null === $window) {
            $window = 30;
        }
        if (null === $futureWindows) {
            $futureWindows = 1;
        }
        if (null === $pastWindows) {
            $pastWindows = 1;
        }

        parent::__construct($digits, $secretLength);

        $this->window = $window;
        $this->futureWindows = $futureWindows;
        $this->pastWindows = $pastWindows;
    }

    /**
     * Get the number of seconds each token is valid for.
     *
     * @return integer The number of seconds each token is valid for.
     */
    public function window()
    {
        return $this->window;
    }

    /**
     * Get the number of future windows to check.
     *
     * @return integer The number of future windows to check.
     */
    public function futureWindows()
    {
        return $this->futureWindows;
    }

    /**
     * Get the number of past windows to check.
     *
     * @return integer The number of past windows to check.
     */
    public function pastWindows()
    {
        return $this->pastWindows;
    }

    private $window;
    private $futureWindows;
    private $pastWindows;
}

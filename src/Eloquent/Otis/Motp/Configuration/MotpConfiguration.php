<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Configuration;

/**
 * Represents a complete set of mOTP configuration settings.
 */
class MotpConfiguration implements MotpConfigurationInterface
{
    /**
     * Construct a new mOTP configuration.
     *
     * @param integer|null $futureWindows The number of future windows to check.
     * @param integer|null $pastWindows   The number of past windows to check.
     */
    public function __construct($futureWindows = null, $pastWindows = null)
    {
        if (null === $futureWindows) {
            $futureWindows = 3;
        }
        if (null === $pastWindows) {
            $pastWindows = 3;
        }

        $this->futureWindows = $futureWindows;
        $this->pastWindows = $pastWindows;
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

    private $futureWindows;
    private $pastWindows;
}

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

/**
 * The interface implemented by counter-based one-time password authentication
 * configurations.
 */
interface CounterBasedOtpConfigurationInterface extends
    OtpConfigurationInterface
{
    /**
     * Set the amount of counter increments to search through for a match.
     *
     * @param integer $window The amount of counter increments to search through for a match.
     */
    public function setWindow($window);

    /**
     * Get the amount of counter increments to search through for a match.
     *
     * @return integer The amount of counter increments to search through for a match.
     */
    public function window();

    /**
     * Get the initial counter value.
     *
     * @return integer The initial counter value.
     */
    public function initialCounter();
}

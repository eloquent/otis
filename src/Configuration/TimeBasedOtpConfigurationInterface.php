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
 * The interface implemented by time-based one-time password authentication
 * configurations.
 */
interface TimeBasedOtpConfigurationInterface extends OtpConfigurationInterface
{
    /**
     * Get the number of seconds each token is valid for.
     *
     * @return integer The number of seconds each token is valid for.
     */
    public function window();

    /**
     * Get the number of future windows to check.
     *
     * @return integer The number of future windows to check.
     */
    public function futureWindows();

    /**
     * Get the number of past windows to check.
     *
     * @return integer The number of past windows to check.
     */
    public function pastWindows();
}

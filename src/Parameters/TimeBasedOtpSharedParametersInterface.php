<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

/**
 * The interface implemented by time-based one-time password shared parameters.
 */
interface TimeBasedOtpSharedParametersInterface extends
    OtpSharedParametersInterface
{
    /**
     * Set the time value.
     *
     * @param integer $time The time in seconds since the Unix epoch.
     */
    public function setTime($time);

    /**
     * Get the time in seconds since the Unix epoch.
     *
     * @return integer The time.
     */
    public function time();
}

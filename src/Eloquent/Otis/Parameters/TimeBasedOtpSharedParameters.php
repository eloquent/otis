<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

use Icecave\Isolator\Isolator;

/**
 * Represents a set of time-based one-time password authentication shared
 * parameters.
 */
class TimeBasedOtpSharedParameters extends AbstractOtpSharedParameters
    implements TimeBasedOtpSharedParametersInterface
{
    /**
     * Construct a new time-based one-time password shared parameters
     * instance.
     *
     * @param string        $secret   The shared secret.
     * @param integer|null  $time     The time in seconds since the Unix epoch.
     * @param Isolator|null $isolator The isolator to use.
     */
    public function __construct(
        $secret,
        $time = null,
        Isolator $isolator = null
    ) {
        if (null === $time) {
            $time = Isolator::get($isolator)->time();
        }

        parent::__construct($secret);

        $this->time = $time;
    }

    /**
     * Get the time in seconds since the Unix epoch.
     *
     * @return integer The time.
     */
    public function time()
    {
        return $this->time;
    }

    private $time;
}

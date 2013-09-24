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

use Eloquent\Otis\Configuration\AbstractTimeBasedOtpConfiguration;

/**
 * Represents a complete set of mOTP configuration settings.
 */
class MotpConfiguration extends AbstractTimeBasedOtpConfiguration implements
    MotpConfigurationInterface
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

        parent::__construct(6, 10, $futureWindows, $pastWindows, 8);
    }
}

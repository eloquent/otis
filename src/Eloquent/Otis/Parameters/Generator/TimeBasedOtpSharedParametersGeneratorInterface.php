<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters\Generator;

use Eloquent\Otis\Configuration\TimeBasedOtpConfigurationInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;

/**
 * The interface implemented by time-based one-time password shared parameter
 * generators.
 */
interface TimeBasedOtpSharedParametersGeneratorInterface
{
    /**
     * Generate a set of time-based one-time password shared parameters.
     *
     * @param TimeBasedOtpConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return TimeBasedOtpSharedParametersInterface The generated shared parameters.
     */
    public function generateTimeBased(
        TimeBasedOtpConfigurationInterface $configuration
    );
}

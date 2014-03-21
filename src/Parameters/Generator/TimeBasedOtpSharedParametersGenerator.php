<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters\Generator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Configuration\TimeBasedOtpConfigurationInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Icecave\Isolator\Isolator;

/**
 * Generates a set of shared parameters for time-based one-time password
 * authentication.
 */
class TimeBasedOtpSharedParametersGenerator implements
    MfaSharedParametersGeneratorInterface,
    TimeBasedOtpSharedParametersGeneratorInterface
{
    /**
     * Construct a new time-based one-time password shared parameters
     * generator.
     *
     * @param Isolator|null $isolator The isolator to use.
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Generate a set of multi-factor authentication shared parameters.
     *
     * @param MfaConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return MfaSharedParametersInterface The generated shared parameters.
     */
    public function generate(MfaConfigurationInterface $configuration)
    {
        return $this->generateTimeBased($configuration);
    }

    /**
     * Generate a set of time-based one-time password shared parameters.
     *
     * @param TimeBasedOtpConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return TimeBasedOtpSharedParametersInterface The generated shared parameters.
     */
    public function generateTimeBased(
        TimeBasedOtpConfigurationInterface $configuration
    ) {
        return new TimeBasedOtpSharedParameters(
            $this->isolator()->mcrypt_create_iv($configuration->secretLength()),
            $this->isolator()->time(),
            $this->isolator()
        );
    }

    /**
     * Get the isolator.
     *
     * @return Isolator The isolator.
     */
    protected function isolator()
    {
        return $this->isolator;
    }

    private $isolator;
}

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

use Eloquent\Otis\Configuration\CounterBasedOtpConfigurationInterface;
use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Icecave\Isolator\Isolator;

/**
 * Generates a set of shared parameters for counter-based one-time password
 * authentication.
 */
class CounterBasedOtpSharedParametersGenerator implements
    MfaSharedParametersGeneratorInterface,
    CounterBasedOtpSharedParametersGeneratorInterface
{
    /**
     * Construct a new counter-based one-time password shared parameters
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
        return $this->generateCounterBased($configuration);
    }

    /**
     * Generate a set of counter-based one-time password shared parameters.
     *
     * @param CounterBasedOtpConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return CounterBasedOtpSharedParametersInterface The generated shared parameters.
     */
    public function generateCounterBased(
        CounterBasedOtpConfigurationInterface $configuration
    ) {
        return new CounterBasedOtpSharedParameters(
            $this->isolator()->mcrypt_create_iv($configuration->secretLength()),
            $configuration->initialCounter()
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

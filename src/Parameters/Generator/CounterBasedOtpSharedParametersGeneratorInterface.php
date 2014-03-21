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
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;

/**
 * The interface implemented by counter-based one-time password shared parameter
 * generators.
 */
interface CounterBasedOtpSharedParametersGeneratorInterface
{
    /**
     * Generate a set of counter-based one-time password shared parameters.
     *
     * @param CounterBasedOtpConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return CounterBasedOtpSharedParametersInterface The generated shared parameters.
     */
    public function generateCounterBased(
        CounterBasedOtpConfigurationInterface $configuration
    );
}

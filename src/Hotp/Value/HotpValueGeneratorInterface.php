<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Value;

use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;

/**
 * The interface implemented by HOTP value generators.
 */
interface HotpValueGeneratorInterface
{
    /**
     * Generate an HOTP value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @param HotpConfigurationInterface               $configuration The configuration to use for generation.
     * @param CounterBasedOtpSharedParametersInterface $shared        The shared parameters to use for generation.
     *
     * @return HotpValueInterface The generated HOTP value.
     */
    public function generateHotp(
        HotpConfigurationInterface $configuration,
        CounterBasedOtpSharedParametersInterface $shared
    );
}

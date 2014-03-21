<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Otp\Value;

use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;

/**
 * The interface implemented by OTP value generators.
 */
interface OtpValueGeneratorInterface
{
    /**
     * Generate an OTP value.
     *
     * @param OtpConfigurationInterface    $configuration The configuration to use for generation.
     * @param OtpSharedParametersInterface $shared        The shared parameters to use for generation.
     *
     * @return OtpValueInterface The generated OTP value.
     */
    public function generate(
        OtpConfigurationInterface $configuration,
        OtpSharedParametersInterface $shared
    );
}

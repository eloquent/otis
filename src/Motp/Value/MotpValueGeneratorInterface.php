<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Value;

use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Parameters\MotpSharedParametersInterface;
use Eloquent\Otis\Otp\Value\OtpValueInterface;

/**
 * The interface implemented by mOTP value generators.
 */
interface MotpValueGeneratorInterface
{
    /**
     * Generate an mOTP value.
     *
     * @link http://motp.sourceforge.net/#1.1
     *
     * @param MotpConfigurationInterface    $configuration The configuration to use for generation.
     * @param MotpSharedParametersInterface $shared        The shared parameters to use for generation.
     *
     * @return OtpValueInterface The generated mOTP value.
     */
    public function generateMotp(
        MotpConfigurationInterface $configuration,
        MotpSharedParametersInterface $shared
    );
}

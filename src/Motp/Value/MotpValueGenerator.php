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

use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Parameters\MotpSharedParametersInterface;
use Eloquent\Otis\Otp\Value\OtpValueGeneratorInterface;
use Eloquent\Otis\Otp\Value\OtpValueInterface;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;

/**
 * Generates mOTP values.
 */
class MotpValueGenerator implements
    OtpValueGeneratorInterface,
    MotpValueGeneratorInterface
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
    ) {
        return $this->generateMotp($configuration, $shared);
    }

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
    ) {
        return new MotpValue(
            md5(
                strval(intval($shared->time() / $configuration->window())) .
                    bin2hex($shared->secret()) .
                    $shared->pin()
            )
        );
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Value;

use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Value\HotpValueGenerator;
use Eloquent\Otis\Hotp\Value\HotpValueGeneratorInterface;
use Eloquent\Otis\Hotp\Value\HotpValueInterface;
use Eloquent\Otis\Otp\Value\OtpValueGeneratorInterface;
use Eloquent\Otis\Otp\Value\OtpValueInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;

/**
 * Generates TOTP values.
 */
class TotpValueGenerator implements
    OtpValueGeneratorInterface,
    TotpValueGeneratorInterface
{
    /**
     * Construct a new TOTP generator.
     *
     * @param HotpValueGeneratorInterface|null $generator The HOTP generator to use.
     */
    public function __construct(HotpValueGeneratorInterface $generator = null)
    {
        if (null === $generator) {
            $generator = new HotpValueGenerator;
        }

        $this->generator = $generator;
    }

    /**
     * Get the HOTP generator.
     *
     * @return HotpValueGeneratorInterface The HOTP generator.
     */
    public function generator()
    {
        return $this->generator;
    }

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
        return $this->generateTotp($configuration, $shared);
    }

    /**
     * Generate a TOTP value.
     *
     * @link http://tools.ietf.org/html/rfc6238#section-4
     *
     * @param TotpConfigurationInterface            $configuration The configuration to use for generation.
     * @param TimeBasedOtpSharedParametersInterface $shared        The shared parameters to use for generation.
     *
     * @return HotpValueInterface The generated TOTP value.
     */
    public function generateTotp(
        TotpConfigurationInterface $configuration,
        TimeBasedOtpSharedParametersInterface $shared
    ) {
        return $this->generator()->generateHotp(
            new HotpConfiguration(
                $configuration->digits(),
                null,
                null,
                $configuration->secretLength(),
                $configuration->algorithm()
            ),
            new CounterBasedOtpSharedParameters(
                $shared->secret(),
                intval(floor($shared->time() / $configuration->window()))
            )
        );
    }

    private $generator;
}

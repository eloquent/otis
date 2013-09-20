<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Generator;

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Generator\HotpGenerator;
use Eloquent\Otis\Hotp\Generator\HotpGeneratorInterface;
use Eloquent\Otis\Hotp\Value\HotpValueInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;

/**
 * Generates TOTP values.
 */
class TotpGenerator implements TotpGeneratorInterface
{
    /**
     * Construct a new TOTP generator.
     *
     * @param HotpGeneratorInterface|null $generator The HOTP generator to use.
     */
    public function __construct(HotpGeneratorInterface $generator = null)
    {
        if (null === $generator) {
            $generator = new HotpGenerator;
        }

        $this->generator = $generator;
    }

    /**
     * Get the HOTP generator.
     *
     * @return HotpGeneratorInterface The HOTP generator.
     */
    public function generator()
    {
        return $this->generator;
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

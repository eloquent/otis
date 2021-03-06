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

use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Otp\Value\OtpValueGeneratorInterface;
use Eloquent\Otis\Otp\Value\OtpValueInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;

/**
 * Generates HOTP values.
 */
class HotpValueGenerator implements
    OtpValueGeneratorInterface,
    HotpValueGeneratorInterface
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
        return $this->generateHotp($configuration, $shared);
    }

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
    ) {
        return new HotpValue(
            hash_hmac(
                $configuration->algorithm()->value(),
                $this->pack($shared->counter()),
                $shared->secret(),
                true
            )
        );
    }

    /**
     * Pack a 64-bit integer into a binary representation.
     *
     * @param integer $integer The integer to pack.
     *
     * @return string The binary representation.
     */
    protected function pack($integer)
    {
        $highPart = ($integer & 0xffffffff00000000) >> 32;
        $lowPart  = ($integer & 0x00000000ffffffff);

        return pack('N2', $highPart, $lowPart);
    }
}

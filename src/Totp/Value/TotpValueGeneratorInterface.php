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

use Eloquent\Otis\Hotp\Value\HotpValueInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;

/**
 * The interface implemented by TOTP value generators.
 */
interface TotpValueGeneratorInterface
{
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
    );
}

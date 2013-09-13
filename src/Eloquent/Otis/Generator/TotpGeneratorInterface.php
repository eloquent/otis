<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Generator;

use Eloquent\Otis\Configuration\HashAlgorithm;

/**
 * The interface implemented by TOTP generators.
 */
interface TotpGeneratorInterface
{
    /**
     * Generate a TOTP value.
     *
     * @link http://tools.ietf.org/html/rfc6238#section-4
     *
     * @param string             $secret    The shared secret.
     * @param integer|null       $window    The number of seconds each value is valid for.
     * @param integer|null       $time      The Unix timestamp to generate the password for.
     * @param HashAlgorithm|null $algorithm The hash algorithm to use.
     *
     * @return OtpValueInterface The generated TOTP value.
     */
    public function generate(
        $secret,
        $window = null,
        $time = null,
        HashAlgorithm $lagorithm = null
    );
}
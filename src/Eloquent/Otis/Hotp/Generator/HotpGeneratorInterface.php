<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Generator;

use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Hotp\Value\HotpValueInterface;

/**
 * The interface implemented by HOTP generators.
 */
interface HotpGeneratorInterface
{
    /**
     * Generate an HOTP value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @param string                 $secret    The shared secret.
     * @param integer                $counter   The counter value.
     * @param HotpHashAlgorithm|null $algorithm The hash algorithm to use.
     *
     * @return HotpValueInterface The generated HOTP value.
     */
    public function generate(
        $secret,
        $counter,
        HotpHashAlgorithm $algorithm = null
    );
}

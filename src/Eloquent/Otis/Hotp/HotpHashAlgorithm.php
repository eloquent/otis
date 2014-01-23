<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * The available HOTP hash algorithms.
 */
final class HotpHashAlgorithm extends AbstractEnumeration
{
    /**
     * The SHA-1 hash algorithm.
     *
     * @link http://tools.ietf.org/html/rfc3174
     */
    const SHA1 = 'SHA1';

    /**
     * The SHA-256 hash algorithm.
     *
     * @link http://tools.ietf.org/html/rfc6234
     */
    const SHA256 = 'SHA256';

    /**
     * The SHA-512 hash algorithm.
     *
     * @link http://tools.ietf.org/html/rfc6234
     */
    const SHA512 = 'SHA512';
}

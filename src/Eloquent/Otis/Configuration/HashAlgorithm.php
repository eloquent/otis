<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

use Eloquent\Enumeration\Enumeration;

/**
 * The available hash algorithms.
 */
final class HashAlgorithm extends Enumeration
{
    /**
     * The SHA-1 hash algorithm.
     *
     * @link http://tools.ietf.org/html/rfc3174
     */
    const SHA1 = 'sha1';

    /**
     * The SHA-256 hash algorithm.
     *
     * @link http://tools.ietf.org/html/rfc6234
     */
    const SHA256 = 'sha256';

    /**
     * The SHA-512 hash algorithm.
     *
     * @link http://tools.ietf.org/html/rfc6234
     */
    const SHA512 = 'sha512';
}

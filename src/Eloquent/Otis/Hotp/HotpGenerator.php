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

/**
 * Generates HOTP values.
 */
class HotpGenerator implements HotpGeneratorInterface
{
    /**
     * Generate an HOTP value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @param string  $secret  The shared secret.
     * @param integer $counter The counter value.
     *
     * @return HotpValue The generated HOTP value.
     */
    public function generate($secret, $counter)
    {
        return new HotpValue(
            hash_hmac('sha1', $this->pack($counter), $secret, true)
        );
    }

    /**
     * @param integer $integer
     *
     * @return string
     */
    protected function pack($integer)
    {
        $highPart = ($integer & 0xffffffff00000000) >> 32;
        $lowPart  = ($integer & 0x00000000ffffffff);

        return pack('N2', $highPart, $lowPart);
    }
}

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
 * The interface implemented by HOTP generators.
 */
interface HotpGeneratorInterface
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
    public function generate($secret, $counter);
}

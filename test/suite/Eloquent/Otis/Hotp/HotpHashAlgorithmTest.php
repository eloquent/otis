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

use PHPUnit_Framework_TestCase;

class HotpHashAlgorithmTest extends PHPUnit_Framework_TestCase
{
    public function testEnumeration()
    {
        $this->assertSame(
            array(
                'SHA1' => HotpHashAlgorithm::SHA1(),
                'SHA256' => HotpHashAlgorithm::SHA256(),
                'SHA512' => HotpHashAlgorithm::SHA512(),
            ),
            HotpHashAlgorithm::members()
        );
    }
}

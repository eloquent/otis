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

use PHPUnit_Framework_TestCase;

class HashAlgorithmTest extends PHPUnit_Framework_TestCase
{
    public function testEnumeration()
    {
        $this->assertSame(
            array(
                'SHA1' => HashAlgorithm::SHA1(),
                'SHA256' => HashAlgorithm::SHA256(),
                'SHA512' => HashAlgorithm::SHA512(),
            ),
            HashAlgorithm::members()
        );
    }
}

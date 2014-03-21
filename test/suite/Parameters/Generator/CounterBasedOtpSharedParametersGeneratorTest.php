<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters\Generator;

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class CounterBasedOtpSharedParametersGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->generator = new CounterBasedOtpSharedParametersGenerator($this->isolator);
    }

    public function testGenerateCounterBased()
    {
        $configuration = new HotpConfiguration(null, null, 111, 222);
        Phake::when($this->isolator)->mcrypt_create_iv(222)->thenReturn('1234567890');
        $actual = $this->generator->generate($configuration);

        $this->assertInstanceOf('Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters', $actual);
        $this->assertSame('1234567890', $actual->secret());
        $this->assertSame(111, $actual->counter());
    }
}

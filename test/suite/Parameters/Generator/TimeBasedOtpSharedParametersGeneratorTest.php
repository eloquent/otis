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

use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class TimeBasedOtpSharedParametersGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->generator = new TimeBasedOtpSharedParametersGenerator($this->isolator);
    }

    public function testGenerateTimeBased()
    {
        $configuration = new TotpConfiguration(null, null, null, null, 111);
        Phake::when($this->isolator)->mcrypt_create_iv(111)->thenReturn('1234567890');
        Phake::when($this->isolator)->time()->thenReturn(222);
        $actual = $this->generator->generate($configuration);

        $this->assertInstanceOf('Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters', $actual);
        $this->assertSame('1234567890', $actual->secret());
        $this->assertSame(222, $actual->time());
    }
}

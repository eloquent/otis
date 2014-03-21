<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

use Icecave\Isolator\Isolator;
use Phake;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters
 * @covers \Eloquent\Otis\Parameters\AbstractOtpSharedParameters
 */
class TimeBasedOtpSharedParametersTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        Phake::when($this->isolator)->time()->thenReturn(222);
        $this->parameters = new TimeBasedOtpSharedParameters('secret', 111, $this->isolator);
    }

    public function testConstructor()
    {
        $this->assertSame('secret', $this->parameters->secret());
        $this->assertSame(111, $this->parameters->time());
    }

    public function testConstructorDefaults()
    {
        $this->parameters = new TimeBasedOtpSharedParameters('secret', null, $this->isolator);

        $this->assertSame(222, $this->parameters->time());
    }

    public function testSetTime()
    {
        $this->parameters->setTime(222);

        $this->assertSame(222, $this->parameters->time());
    }
}

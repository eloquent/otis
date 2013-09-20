<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Parameters;

use Icecave\Isolator\Isolator;
use Phake;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Motp\Parameters\MotpSharedParameters
 * @covers \Eloquent\Otis\Parameters\AbstractOtpSharedParameters
 */
class MotpSharedParametersTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        Phake::when($this->isolator)->time()->thenReturn(222);
        $this->parameters = new MotpSharedParameters('secret', 1234, 111, $this->isolator);
    }

    public function testConstructor()
    {
        $this->assertSame('secret', $this->parameters->secret());
        $this->assertSame(1234, $this->parameters->pin());
        $this->assertSame(111, $this->parameters->time());
    }

    public function testConstructorDefaults()
    {
        $this->parameters = new MotpSharedParameters('secret', 1234, null, $this->isolator);

        $this->assertSame(222, $this->parameters->time());
    }
}

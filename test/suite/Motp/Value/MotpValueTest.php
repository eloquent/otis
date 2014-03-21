<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Value;

use PHPUnit_Framework_TestCase;

class MotpValueTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->value = new MotpValue('12345678901234567890123456789012');
    }

    public function testValue()
    {
        $this->assertSame('12345678901234567890123456789012', $this->value->value());
        $this->assertSame('123456', strval($this->value));
        $this->assertSame('123456', $this->value->string());
        $this->assertSame('123456', $this->value->string(6));
        $this->assertSame('12345678901234567890123456789012', $this->value->string(32));
    }

    public function testStringFailureTooShort()
    {
        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        $this->value->string(5);
    }

    public function testStringFailureTooLong()
    {
        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        $this->value->string(33);
    }
}

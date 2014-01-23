<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Value;

use PHPUnit_Framework_TestCase;

class HotpValueTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $value = new HotpValue('AAAAAAAAAAAAAAAAAAAA');

        $this->assertSame('AAAAAAAAAAAAAAAAAAAA', $value->value());
        $this->assertSame(1094795585, $value->truncated());
    }

    public function stringData()
    {
        //                             value                   length expected
        return array(
            'Default length'  => array('AAAAAAAAAAAAAAAAAAAA', null,  '795585'),
            '6 digit A'       => array('AAAAAAAAAAAAAAAAAAAA', 6,     '795585'),
            '8 digit A'       => array('AAAAAAAAAAAAAAAAAAAA', 8,     '94795585'),
            '10 digit A'      => array('AAAAAAAAAAAAAAAAAAAA', 10,    '1094795585'),
            '6 digit B'       => array('BBBBBBBBBBBBBBBBBBBB', 6,     '638594'),
            '8 digit B'       => array('BBBBBBBBBBBBBBBBBBBB', 8,     '11638594'),
            '10 digit B'      => array('BBBBBBBBBBBBBBBBBBBB', 10,    '1111638594'),
        );
    }

    /**
     * @dataProvider stringData
     */
    public function testString($value, $length, $expected)
    {
        $value = new HotpValue($value);

        $this->assertEquals($expected, $value->string($length));
    }

    public function testStringFailureInvalidLengthTooShort()
    {
        $value = new HotpValue('AAAAAAAAAAAAAAAAAAAA');

        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        $value->string(5);
    }

    public function testStringFailureInvalidLengthTooLong()
    {
        $value = new HotpValue('AAAAAAAAAAAAAAAAAAAA');

        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        $value->string(11);
    }

    public function testToString()
    {
        $this->assertSame('795585', strval(new HotpValue('AAAAAAAAAAAAAAAAAAAA')));
    }
}

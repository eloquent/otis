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

class HotpValueTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $result = new HotpValue('AAAAAAAAAAAAAAAAAAAA');

        $this->assertSame('AAAAAAAAAAAAAAAAAAAA', $result->value());
        $this->assertSame(1094795585, $result->truncated());
    }

    public function testConstructorFailureInvalidLength()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidResultLengthException');
        new HotpValue('AAAAAAAAAAAAAAAAAAA');
    }

    public function stringData()
    {
        return array(
            'Default length'  => array('AAAAAAAAAAAAAAAAAAAA', null, '795585'),
            '6 digit A'       => array('AAAAAAAAAAAAAAAAAAAA', 6,    '795585'),
            '8 digit A'       => array('AAAAAAAAAAAAAAAAAAAA', 8,    '94795585'),
            '10 digit A'      => array('AAAAAAAAAAAAAAAAAAAA', 10,   '1094795585'),
            '6 digit B'       => array('BBBBBBBBBBBBBBBBBBBB', 6,    '638594'),
            '8 digit B'       => array('BBBBBBBBBBBBBBBBBBBB', 8,    '11638594'),
            '10 digit B'      => array('BBBBBBBBBBBBBBBBBBBB', 10,   '1111638594'),
        );
    }

    /**
     * @dataProvider stringData
     */
    public function testString($value, $length, $expected)
    {
        $result = new HotpValue($value);

        $this->assertEquals($expected, $result->string($length));
    }

    public function stringFailureInvalidLengthData()
    {
        return array(
            'Negative'        => array(-1),
            'Zero'            => array(0),
            'Less than 6'     => array(5),
            'Greater than 10' => array(11),
        );
    }

    /**
     * @dataProvider stringFailureInvalidLengthData
     */
    public function testStringFailureInvalidLength($length)
    {
        $result = new HotpValue('AAAAAAAAAAAAAAAAAAAA');

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidOutputLengthException');
        $result->string($length);
    }
}

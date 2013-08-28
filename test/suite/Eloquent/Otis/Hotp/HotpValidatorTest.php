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

class HotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new HotpGenerator;
        $this->validator = new HotpValidator($this->generator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->generator, $this->validator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new HotpValidator;

        $this->assertEquals($this->generator, $this->validator->generator());
    }

    public function validateData()
    {
        //                                  password  secret                  currentCounter digits window expected newCounter
        return array(
            'No window, valid'     => array('969429', '12345678901234567890', 3,             null,  null,  true,    4),
            'With window, valid'   => array('520489', '12345678901234567890', 0,             null,  9,     true,    10),

            'No window, invalid'   => array('338314', '12345678901234567890', 3,             null,  null,  false,   3),
            'With window, invalid' => array('520489', '12345678901234567890', 0,             null,  8,     false,   0),
            'Length mismatch'      => array('969429', '12345678901234567890', 3,             8,     null,  false,   3),
            'Invalid length'       => array('12345',  '12345678901234567890', 0,             5,     100,   false,   0),
        );
    }

    /**
     * @dataProvider validateData
     */
    public function testValidate($password, $secret, $currentCounter, $digits, $window, $expected, $expectedCounter)
    {
        $this->assertSame(
            $expected,
            $this->validator->validate($password, $secret, $currentCounter, $newCounter, $digits, $window)
        );
        $this->assertSame($expectedCounter, $newCounter);
    }

    public function validateSequenceData()
    {
        //                                  passwords                  secret                  currentCounter digits window expected newCounter
        return array(
            'No window, valid'     => array(array('969429', '338314'), '12345678901234567890', 3,             null,  null,  true,    5),
            'With window, valid'   => array(array('399871', '520489'), '12345678901234567890', 0,             null,  8,     true,    10),

            'No window, invalid'   => array(array('359152', '969429'), '12345678901234567890', 3,             null,  null,  false,   3),
            'With window, invalid' => array(array('755224', '359152'), '12345678901234567890', 0,             null,  100,   false,   0),
            'Length mismatch'      => array(array('969429', '338314'), '12345678901234567890', 3,             8,     null,  false,   3),
            'Invalid length'       => array(array('755224', '12345'),  '12345678901234567890', 0,             null,  100,   false,   0),
            'No passwords'         => array(array(),                   '12345678901234567890', 0,             null,  100,   false,   0),
        );
    }

    /**
     * @dataProvider validateSequenceData
     */
    public function testValidateSequence($passwords, $secret, $currentCounter, $digits, $window, $expected, $expectedCounter)
    {
        $this->assertSame(
            $expected,
            $this->validator->validateSequence($passwords, $secret, $currentCounter, $newCounter, $digits, $window)
        );
        $this->assertSame($expectedCounter, $newCounter);
    }
}

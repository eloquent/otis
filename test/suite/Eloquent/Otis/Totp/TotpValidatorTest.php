<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp;

use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class TotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new TotpGenerator;
        $this->isolator = Phake::mock(Isolator::className());
        $this->validator = new TotpValidator($this->generator, $this->isolator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->generator, $this->validator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new TotpValidator;

        $this->assertEquals($this->generator, $this->validator->generator());
    }

    public function validateData()
    {
        //                                     password    secret                  digits window time        pastWindows futureWindows expected drift
        return array(
            'Valid, no drift'         => array('14050471', '12345678901234567890', 8,     null,  1111111111, null,       null,         true,    0),
            'Valid, 1 past drift'     => array('07081804', '12345678901234567890', 8,     null,  1111111111, null,       null,         true,    -1),
            'Valid, 1 future drift'   => array('44266759', '12345678901234567890', 8,     null,  1111111111, null,       null,         true,    1),
            'Valid, 10 past drift'    => array('13755423', '12345678901234567890', 8,     null,  1111111111, 100,        100,          true,    -10),
            'Valid, 10 future drift'  => array('78536305', '12345678901234567890', 8,     null,  1111111111, 100,        100,          true,    10),

            'Invalid, too far past'   => array('13755423', '12345678901234567890', 8,     null,  1111111111, 9,          null,         false,   null),
            'Invalid, too far future' => array('78536305', '12345678901234567890', 8,     null,  1111111111, null,       9,            false,   null),
            'Length mismatch'         => array('14050471', '12345678901234567890', null,  null,  1111111111, null,       null,         false,   null),
            'Invalid length'          => array('12345',    '12345678901234567890', 5,     null,  1111111111, null,       null,         false,   null),
        );
    }

    /**
     * @dataProvider validateData
     */
    public function testValidate($password, $secret, $digits, $window, $time, $pastWindows, $futureWindows, $expected, $expectedDrift)
    {
        Phake::when($this->isolator)->time()->thenReturn($time);

        $this->assertSame(
            $expected,
            $this->validator->validate($password, $secret, $digits, $window, $pastWindows, $futureWindows, $actualDrift)
        );
        $this->assertSame($expectedDrift, $actualDrift);
    }
}

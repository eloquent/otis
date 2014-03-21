<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator;

use Eloquent\Otis\Credentials\OtpCredentials;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use Eloquent\Otis\Motp\Value\MotpValueGenerator;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Value\TotpValueGenerator;
use PHPUnit_Framework_TestCase;
use Phake;

/**
 * @covers \Eloquent\Otis\Validator\TimeBasedOtpValidator
 * @covers \Eloquent\Otis\Validator\AbstractOtpValidator
 */
class TimeBasedOtpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = Phake::mock('Eloquent\Otis\Otp\Value\OtpValueGeneratorInterface');
        $this->validator = new TimeBasedOtpValidator($this->generator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->generator, $this->validator->generator());
    }

    public function validateTotpData()
    {
        //                                     password    secret                  digits window time        pastWindows futureWindows result                        drift
        return array(
            'Valid, no drift'         => array('14050471', '12345678901234567890', 8,     null,  1111111111, null,       null,         'valid',                      0),
            'Valid, 1 past drift'     => array('07081804', '12345678901234567890', 8,     null,  1111111111, null,       null,         'valid',                      -1),
            'Valid, 1 future drift'   => array('44266759', '12345678901234567890', 8,     null,  1111111111, null,       null,         'valid',                      1),
            'Valid, 10 past drift'    => array('13755423', '12345678901234567890', 8,     null,  1111111111, 100,        100,          'valid',                      -10),
            'Valid, 10 future drift'  => array('78536305', '12345678901234567890', 8,     null,  1111111111, 100,        100,          'valid',                      10),

            'Invalid, too far past'   => array('13755423', '12345678901234567890', 8,     null,  1111111111, 9,          null,         'invalid-credentials',        null),
            'Invalid, too far future' => array('78536305', '12345678901234567890', 8,     null,  1111111111, null,       9,            'invalid-credentials',        null),
            'Length mismatch'         => array('14050471', '12345678901234567890', null,  null,  1111111111, null,       null,         'credential-length-mismatch', null),
        );
    }

    /**
     * @dataProvider validateTotpData
     */
    public function testValidateTotp($password, $secret, $digits, $window, $time, $pastWindows, $futureWindows, $result, $drift)
    {
        $this->generator = new TotpValueGenerator;
        $this->validator = new TimeBasedOtpValidator($this->generator);
        $configuration = new TotpConfiguration($digits, $window, $futureWindows, $pastWindows);
        $shared = new TimeBasedOtpSharedParameters($secret, $time);
        $credentials = new OtpCredentials($password);
        $actual = $this->validator->validate($configuration, $shared, $credentials);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($drift, $actual->drift());
    }

    public function validateMotpData()
    {
        //                                     password   secret      pin   time        pastWindows futureWindows result                        drift
        return array(
            'Valid, no drift'         => array('3fadec',  '12345678', 1234, 1111111111, null,       null,         'valid',                      0),
            'Valid, 3 past drift'     => array('81d313',  '12345678', 1234, 1111111111, null,       null,         'valid',                      -3),
            'Valid, 3 future drift'   => array('f5521c',  '12345678', 1234, 1111111111, null,       null,         'valid',                      3),
            'Valid, 10 past drift'    => array('1ea954',  '12345678', 1234, 1111111111, 100,        100,          'valid',                      -10),
            'Valid, 10 future drift'  => array('69bfeb',  '12345678', 1234, 1111111111, 100,        100,          'valid',                      10),

            'Invalid, too far past'   => array('1ea954',  '12345678', 1234, 1111111111, 9,          null,         'invalid-credentials',        null),
            'Invalid, too far future' => array('69bfeb',  '12345678', 1234, 1111111111, null,       9,            'invalid-credentials',        null),
            'Length mismatch'         => array('1234567', '12345678', 1234, 1111111111, null,       null,         'credential-length-mismatch', null),
        );
    }

    /**
     * @dataProvider validateMotpData
     */
    public function testValidateMotp($password, $secret, $pin, $time, $pastWindows, $futureWindows, $result, $drift)
    {
        $this->generator = new MotpValueGenerator;
        $this->validator = new TimeBasedOtpValidator($this->generator);
        $configuration = new MotpConfiguration($futureWindows, $pastWindows);
        $shared = new MotpSharedParameters($secret, $pin, $time);
        $credentials = new OtpCredentials($password);
        $actual = $this->validator->validate($configuration, $shared, $credentials);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($drift, $actual->drift());
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Validator;

use Eloquent\Otis\Credentials\OtpCredentials;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Generator\TotpGenerator;
use PHPUnit_Framework_TestCase;

class TotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new TotpGenerator;
        $this->validator = new TotpValidator($this->generator);
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
        $configuration = new TotpConfiguration($digits, $window, $futureWindows, $pastWindows);
        $shared = new TimeBasedOtpSharedParameters($secret, $time);
        $credentials = new OtpCredentials($password);
        $actual = $this->validator->validate($configuration, $shared, $credentials);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($drift, $actual->drift());
    }
}

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

use Eloquent\Otis\Configuration\HashAlgorithm;
use Eloquent\Otis\Configuration\HotpConfiguration;
use Eloquent\Otis\Configuration\TotpConfiguration;
use Eloquent\Otis\Generator\HotpGenerator;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

/**
 * @covers \Eloquent\Otis\Validator\OtpValidator
 * @covers \Eloquent\Otis\Validator\TotpValidator
 * @covers \Eloquent\Otis\Validator\HotpValidator
 */
class OtpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->totpValidator = new TotpValidator(null, $this->isolator);
        $this->hotpValidator = new HotpValidator;
        $this->validator = new OtpValidator($this->totpValidator, $this->hotpValidator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->totpValidator, $this->validator->totpValidator());
        $this->assertSame($this->hotpValidator, $this->validator->hotpValidator());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new OtpValidator;

        $this->assertInstanceOf(get_class($this->totpValidator), $this->validator->totpValidator());
        $this->assertInstanceOf(get_class($this->hotpValidator), $this->validator->hotpValidator());
    }

    public function validateTotpData()
    {
        //                                     password    secret                  digits window time        pastWindows futureWindows result                      drift
        return array(
            'Valid, no drift'         => array('14050471', '12345678901234567890', 8,     null,  1111111111, null,       null,         'valid',                    0),
            'Valid, 1 past drift'     => array('07081804', '12345678901234567890', 8,     null,  1111111111, null,       null,         'valid',                    -1),
            'Valid, 1 future drift'   => array('44266759', '12345678901234567890', 8,     null,  1111111111, null,       null,         'valid',                    1),
            'Valid, 10 past drift'    => array('13755423', '12345678901234567890', 8,     null,  1111111111, 100,        100,          'valid',                    -10),
            'Valid, 10 future drift'  => array('78536305', '12345678901234567890', 8,     null,  1111111111, 100,        100,          'valid',                    10),

            'Invalid, too far past'   => array('13755423', '12345678901234567890', 8,     null,  1111111111, 9,          null,         'invalid-password',         null),
            'Invalid, too far future' => array('78536305', '12345678901234567890', 8,     null,  1111111111, null,       9,            'invalid-password',         null),
            'Length mismatch'         => array('14050471', '12345678901234567890', null,  null,  1111111111, null,       null,         'password-length-mismatch', null),
        );
    }

    /**
     * @dataProvider validateTotpData
     */
    public function testValidateTotp($password, $secret, $digits, $window, $time, $pastWindows, $futureWindows, $result, $drift)
    {
        Phake::when($this->isolator)->time()->thenReturn($time);
        $configuration = new TotpConfiguration($digits, $window, $futureWindows, $pastWindows);
        $parameters = new Parameters\TotpParameters($secret, $password);
        $actual = $this->validator->validate($configuration, $parameters);

        $this->assertInstanceOf(__NAMESPACE__ . '\Result\TotpValidationResult', $actual);
        $this->assertSame($result, $actual->type()->value());
        $this->assertSame($drift, $actual->drift());
    }

    public function validateHotpData()
    {
        //                                  password  secret                  currentCounter digits window result                      newCounter
        return array(
            'No window, valid'     => array('969429', '12345678901234567890', 3,             null,  null,  'valid',                    4),
            'With window, valid'   => array('520489', '12345678901234567890', 0,             null,  9,     'valid',                    10),

            'No window, invalid'   => array('338314', '12345678901234567890', 3,             null,  0,     'invalid-password',         null),
            'With window, invalid' => array('520489', '12345678901234567890', 0,             null,  8,     'invalid-password',         null),
            'Length mismatch'      => array('969429', '12345678901234567890', 3,             8,     null,  'password-length-mismatch', null),
        );
    }

    /**
     * @dataProvider validateHotpData
     */
    public function testValidateHotp($password, $secret, $currentCounter, $digits, $window, $result, $newCounter)
    {
        $configuration = new HotpConfiguration($digits, $window);
        $parameters = new Parameters\HotpParameters($secret, $password, $currentCounter);
        $actual = $this->validator->validate($configuration, $parameters);

        $this->assertInstanceOf(__NAMESPACE__ . '\Result\HotpValidationResult', $actual);
        $this->assertSame($result, $actual->type()->value());
        $this->assertSame($newCounter, $actual->counter());
    }

    public function testValidateFailureUnsupportedConfiguration()
    {
        $configuration = Phake::mock('Eloquent\Otis\Configuration\OtpConfigurationInterface');
        $parameters = new Parameters\TotpParameters('secret', 'password');

        $this->setExpectedException(__NAMESPACE__ . '\Exception\UnsupportedOtpConfigurationException');
        $this->validator->validate($configuration, $parameters);
    }

    public function testValidateFailureParametersMismatchTotp()
    {
        $configuration = new TotpConfiguration;
        $parameters = Phake::mock(__NAMESPACE__ . '\Parameters\OtpParametersInterface');

        $this->setExpectedException(__NAMESPACE__ . '\Exception\OtpParametersTypeMismatchException');
        $this->validator->validate($configuration, $parameters);
    }

    public function testValidateFailureParametersMismatchHotp()
    {
        $configuration = new HotpConfiguration;
        $parameters = Phake::mock(__NAMESPACE__ . '\Parameters\OtpParametersInterface');

        $this->setExpectedException(__NAMESPACE__ . '\Exception\OtpParametersTypeMismatchException');
        $this->validator->validate($configuration, $parameters);
    }
}
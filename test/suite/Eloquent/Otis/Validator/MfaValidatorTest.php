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
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Validator\HotpValidator;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use Eloquent\Otis\Motp\Validator\MotpValidator;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Validator\TotpValidator;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

/**
 * @covers \Eloquent\Otis\Validator\MfaValidator
 * @covers \Eloquent\Otis\Hotp\Validator\HotpValidator
 * @covers \Eloquent\Otis\Motp\Validator\MotpValidator
 * @covers \Eloquent\Otis\Totp\Validator\TotpValidator
 */
class MfaValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->totpValidator = new TotpValidator;
        $this->hotpValidator = new HotpValidator;
        $this->motpValidator = new MotpValidator(null, $this->isolator);
        $this->validators = array($this->totpValidator, $this->hotpValidator, $this->motpValidator);
        $this->validator = new MfaValidator($this->validators);
    }

    public function testConstructor()
    {
        $this->assertSame($this->validators, $this->validator->validators());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new MfaValidator;
        $validators = $this->validator->validators();

        $this->assertArrayHasKey(0, $validators);
        $this->assertEquals(new TotpValidator, $validators[0]);
        $this->assertArrayHasKey(1, $validators);
        $this->assertEquals(new HotpValidator, $validators[1]);
        $this->assertArrayHasKey(2, $validators);
        $this->assertEquals(new MotpValidator, $validators[2]);
    }

    public function supportsData()
    {
        //                                     configuration          shared                                              credentials                     expected
        return array(
            'TOTP'                    => array(new TotpConfiguration, new TimeBasedOtpSharedParameters('secret', 123),    new OtpCredentials('password'), true),
            'HOTP'                    => array(new HotpConfiguration, new CounterBasedOtpSharedParameters('secret', 111), new OtpCredentials('password'), true),
            'mOTP'                    => array(new MotpConfiguration, new MotpSharedParameters('secret', 1234),           new OtpCredentials('password'), true),
            'Unsupported combination' => array(new HotpConfiguration, new TimeBasedOtpSharedParameters('secret', 123),    new OtpCredentials('password'), false),
        );
    }

    /**
     * @dataProvider supportsData
     */
    public function testSupports($configuration, $shared, $credentials, $expected)
    {
        $this->assertSame($expected, $this->validator->supports($configuration, $shared, $credentials));
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
        Phake::when($this->isolator)->time()->thenReturn($time);
        $configuration = new TotpConfiguration($digits, $window, $futureWindows, $pastWindows);
        $shared = new TimeBasedOtpSharedParameters($secret, $time, $this->isolator);
        $credentials = new OtpCredentials($password);
        $actual = $this->validator->validate($configuration, $shared, $credentials);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($drift, $actual->drift());
    }

    public function validateHotpData()
    {
        //                                  password  secret                  currentCounter digits window result                        newCounter
        return array(
            'No window, valid'     => array('969429', '12345678901234567890', 3,             null,  null,  'valid',                      4),
            'With window, valid'   => array('520489', '12345678901234567890', 0,             null,  9,     'valid',                      10),

            'No window, invalid'   => array('338314', '12345678901234567890', 3,             null,  0,     'invalid-credentials',        null),
            'With window, invalid' => array('520489', '12345678901234567890', 0,             null,  8,     'invalid-credentials',        null),
            'Length mismatch'      => array('969429', '12345678901234567890', 3,             8,     null,  'credential-length-mismatch', null),
        );
    }

    /**
     * @dataProvider validateHotpData
     */
    public function testValidateHotp($password, $secret, $currentCounter, $digits, $window, $result, $newCounter)
    {
        $configuration = new HotpConfiguration($digits, $window);
        $shared = new CounterBasedOtpSharedParameters($secret, $currentCounter);
        $credentials = new OtpCredentials($password);
        $actual = $this->validator->validate($configuration, $shared, $credentials);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\CounterBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($newCounter, $actual->counter());
    }

    public function supportsSequenceData()
    {
        $mockCredentials = Phake::mock('Eloquent\Otis\Credentials\MfaCredentialsInterface');

        //                                     configuration          shared                                              credentialSequence                                                     expected
        return array(
            'HOTP'                    => array(new HotpConfiguration, new CounterBasedOtpSharedParameters('secret', 111), array(new OtpCredentials('password'), new OtpCredentials('password')), true),
            'Unsupported combination' => array(new TotpConfiguration, new TimeBasedOtpSharedParameters('secret', 111),    array(new OtpCredentials('password'), $mockCredentials),               false),
        );
    }

    /**
     * @dataProvider supportsSequenceData
     */
    public function testSupportsSequence($configuration, $shared, $credentialSequence, $expected)
    {
        $this->assertSame($expected, $this->validator->supportsSequence($configuration, $shared, $credentialSequence));
    }

    public function validateHotpSequenceData()
    {
        //                                  passwords                  secret                  currentCounter digits window result                        newCounter
        return array(
            'No window, valid'     => array(array('969429', '338314'), '12345678901234567890', 3,             null,  null,  'valid',                      5),
            'With window, valid'   => array(array('399871', '520489'), '12345678901234567890', 0,             null,  8,     'valid',                      10),

            'No window, invalid'   => array(array('359152', '969429'), '12345678901234567890', 3,             null,  0,     'invalid-credentials',        null),
            'With window, invalid' => array(array('755224', '359152'), '12345678901234567890', 0,             null,  100,   'invalid-credentials',        null),
            'Length mismatch'      => array(array('969429', '338314'), '12345678901234567890', 3,             8,     null,  'credential-length-mismatch', null),
            'No credentials'       => array(array(),                   '12345678901234567890', 0,             null,  100,   'empty-credential-sequence',  null),
        );
    }

    /**
     * @dataProvider validateHotpSequenceData
     */
    public function testValidateHotpSequence($passwords, $secret, $currentCounter, $digits, $window, $result, $newCounter)
    {
        $configuration = new HotpConfiguration($digits, $window);
        $shared = new CounterBasedOtpSharedParameters($secret, $currentCounter);
        $credentialSequence = array();
        foreach ($passwords as $password) {
            $credentialSequence[] = new OtpCredentials($password);
        }
        $actual = $this->validator->validateSequence($configuration, $shared, $credentialSequence);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\CounterBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($newCounter, $actual->counter());
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
        Phake::when($this->isolator)->time()->thenReturn($time);
        $configuration = new MotpConfiguration($futureWindows, $pastWindows);
        $shared = new MotpSharedParameters($secret, $pin, $time, $this->isolator);
        $credentials = new OtpCredentials($password);
        $actual = $this->validator->validate($configuration, $shared, $credentials);

        $this->assertInstanceOf('Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($drift, $actual->drift());
    }

    public function testValidateFailureUnsupportedCombination()
    {
        $configuration = new HotpConfiguration;
        $shared = new TimeBasedOtpSharedParameters('secret', 123);
        $credentials = new OtpCredentials('password');

        $this->setExpectedException(__NAMESPACE__ . '\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $shared, $credentials);
    }

    public function testValidateFailureUnsupportedCombinationSequence()
    {
        $configuration = new TotpConfiguration;
        $shared = new TimeBasedOtpSharedParameters('secret', 123);
        $credentialSequence = array(
            new OtpCredentials('password'),
            Phake::mock('Eloquent\Otis\Credentials\MfaCredentialsInterface'),
        );

        $this->setExpectedException(__NAMESPACE__ . '\Exception\UnsupportedMfaCombinationException');
        $this->validator->validateSequence($configuration, $shared, $credentialSequence);
    }
}
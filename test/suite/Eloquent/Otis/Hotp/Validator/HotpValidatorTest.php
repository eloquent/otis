<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Validator;

use Eloquent\Otis\Credentials\OtpCredentials;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Generator\HotpGenerator;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;
use Phake;

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

    public function supportsData()
    {
        $mockCredentials = Phake::mock('Eloquent\Otis\Credentials\MfaCredentialsInterface');
        $mockSharedParameters = Phake::mock('Eloquent\Otis\Parameters\MfaSharedParametersInterface');

        //                                           configuration          shared                                              credentials                     expected
        return array(
            'Valid combination'             => array(new HotpConfiguration, new CounterBasedOtpSharedParameters('secret', 123), new OtpCredentials('password'), true),
            'Unsupported credentials'       => array(new HotpConfiguration, new CounterBasedOtpSharedParameters('secret', 123), $mockCredentials,               false),
            'Unsupported shared parameters' => array(new HotpConfiguration, $mockSharedParameters,                              new OtpCredentials('password'), false),
            'Unsupported configuration'     => array(new TotpConfiguration, new CounterBasedOtpSharedParameters('secret', 123), new OtpCredentials('password'), false),
        );
    }

    /**
     * @dataProvider supportsData
     */
    public function testSupports($configuration, $shared, $credentials, $expected)
    {
        $this->assertSame($expected, $this->validator->supports($configuration, $shared, $credentials));
    }

    public function testValidateFailureUnsupported()
    {
        $configuration = new TotpConfiguration;
        $shared = new TimeBasedOtpSharedParameters('secret', 123);
        $credentials = new OtpCredentials('password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $shared, $credentials);
    }

    public function testValidateSequenceFailureUnsupportedConfig()
    {
        $configuration = new TotpConfiguration;
        $shared = new TimeBasedOtpSharedParameters('secret', 123);
        $credentialSequence = array(
            new OtpCredentials('password'),
        );

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validateSequence($configuration, $shared, $credentialSequence);
    }

    public function testValidateSequenceFailureUnsupportedCredential()
    {
        $configuration = new HotpConfiguration;
        $shared = new CounterBasedOtpSharedParameters('secret', 123);
        $credentialSequence = array(
            new OtpCredentials('password'),
            Phake::mock('Eloquent\Otis\Credentials\MfaCredentialsInterface'),
        );

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validateSequence($configuration, $shared, $credentialSequence);
    }
}

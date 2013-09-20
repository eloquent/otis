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
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Generator\TotpGenerator;
use PHPUnit_Framework_TestCase;
use Phake;

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

    public function supportsData()
    {
        $mockCredentials = Phake::mock('Eloquent\Otis\Credentials\MfaCredentialsInterface');
        $mockSharedParameters = Phake::mock('Eloquent\Otis\Parameters\MfaSharedParametersInterface');

        //                                           configuration          shared                                           credentials                     expected
        return array(
            'Valid combination'             => array(new TotpConfiguration, new TimeBasedOtpSharedParameters('secret', 123), new OtpCredentials('password'), true),
            'Unsupported credentials'       => array(new TotpConfiguration, new TimeBasedOtpSharedParameters('secret', 123), $mockCredentials,               false),
            'Unsupported shared parameters' => array(new TotpConfiguration, $mockSharedParameters,                           new OtpCredentials('password'), false),
            'Unsupported configuration'     => array(new HotpConfiguration, new TimeBasedOtpSharedParameters('secret', 123), new OtpCredentials('password'), false),
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
        $configuration = new HotpConfiguration;
        $shared = Phake::mock('Eloquent\Otis\Parameters\MfaSharedParametersInterface');
        $credentials = new OtpCredentials('password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $shared, $credentials);
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator;

use Eloquent\Otis\Credentials\OtpCredentials;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Motp\Generator\MotpGenerator;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;
use Phake;

class MotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new MotpGenerator;
        $this->validator = new MotpValidator($this->generator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->generator, $this->validator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new MotpValidator;

        $this->assertEquals($this->generator, $this->validator->generator());
    }

    public function supportsData()
    {
        $mockCredentials = Phake::mock('Eloquent\Otis\Credentials\MfaCredentialsInterface');
        $mockSharedParameters = Phake::mock('Eloquent\Otis\Parameters\MfaSharedParametersInterface');

        //                                           configuration          shared                                   credentials                     expected
        return array(
            'Valid combination'             => array(new MotpConfiguration, new MotpSharedParameters('secret', 123), new OtpCredentials('password'), true),
            'Unsupported credentials'       => array(new MotpConfiguration, new MotpSharedParameters('secret', 123), $mockCredentials,               false),
            'Unsupported shared parameters' => array(new MotpConfiguration, $mockSharedParameters,                   new OtpCredentials('password'), false),
            'Unsupported configuration'     => array(new HotpConfiguration, new MotpSharedParameters('secret', 123), new OtpCredentials('password'), false),
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

        $this->setExpectedException('Eloquent\Otis\Exception\UnsupportedArgumentsException');
        $this->validator->validate($configuration, $shared, $credentials);
    }
}

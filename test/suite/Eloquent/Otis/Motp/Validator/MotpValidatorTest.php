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

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Credentials\HotpCredentials;
use Eloquent\Otis\Hotp\Parameters\HotpSharedParameters;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Motp\Credentials\MotpCredentials;
use Eloquent\Otis\Motp\Generator\MotpGenerator;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Credentials\TotpCredentials;
use Eloquent\Otis\Totp\Parameters\TotpSharedParameters;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class MotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new MotpGenerator;
        $this->isolator = Phake::mock(Isolator::className());
        $this->validator = new MotpValidator($this->generator, $this->isolator);
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
        //                                           configuration          shared                                   credentials                      expected
        return array(
            'Valid combination'             => array(new MotpConfiguration, new MotpSharedParameters('secret', 123), new MotpCredentials('password'), true),
            'Unsupported credentials'       => array(new MotpConfiguration, new MotpSharedParameters('secret', 123), new HotpCredentials('password'), false),
            'Unsupported shared parameters' => array(new MotpConfiguration, new HotpSharedParameters('secret', 123), new MotpCredentials('password'), false),
            'Unsupported configuration'     => array(new HotpConfiguration, new MotpSharedParameters('secret', 123), new MotpCredentials('password'), false),
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
        $shared = new TotpSharedParameters('secret');
        $credentials = new TotpCredentials('password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $shared, $credentials);
    }
}

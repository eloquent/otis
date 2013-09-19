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

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Credentials\HotpCredentials;
use Eloquent\Otis\Hotp\Parameters\HotpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Credentials\TotpCredentials;
use Eloquent\Otis\Totp\Generator\TotpGenerator;
use Eloquent\Otis\Totp\Parameters\TotpSharedParameters;
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

    public function supportsData()
    {
        //                                           configuration          shared                                   credentials                      expected
        return array(
            'Valid combination'             => array(new TotpConfiguration, new TotpSharedParameters('secret'),      new TotpCredentials('password'), true),
            'Unsupported credentials'       => array(new TotpConfiguration, new TotpSharedParameters('secret'),      new HotpCredentials('password'), false),
            'Unsupported shared parameters' => array(new TotpConfiguration, new HotpSharedParameters('secret', 123), new TotpCredentials('password'), false),
            'Unsupported configuration'     => array(new HotpConfiguration, new TotpSharedParameters('secret'),      new TotpCredentials('password'), false),
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
        $shared = new HotpSharedParameters('secret', 111);
        $credentials = new HotpCredentials('password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $shared, $credentials);
    }
}

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
use Eloquent\Otis\Hotp\Validator\Parameters\HotpParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Generator\TotpGenerator;
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
        //                                       configuration          parameters                                           expected
        return array(
            'Valid combination'         => array(new TotpConfiguration, new Parameters\TotpParameters('secret', 'password'), true),
            'Unsupported parameters'    => array(new TotpConfiguration, new HotpParameters('secret', 111, 'password'),       false),
            'Unsupported configuration' => array(new HotpConfiguration, new Parameters\TotpParameters('secret', 'password'), false),
        );
    }

    /**
     * @dataProvider supportsData
     */
    public function testSupports($configuration, $parameters, $expected)
    {
        $this->assertSame($expected, $this->validator->supports($configuration, $parameters));
    }

    public function testValidateFailureUnsupported()
    {
        $configuration = new HotpConfiguration;
        $parameters = new HotpParameters('secret', 111, 'password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $parameters);
    }
}

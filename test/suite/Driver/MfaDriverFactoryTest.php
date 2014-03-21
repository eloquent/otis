<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Driver;

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Phake;
use PHPUnit_Framework_TestCase;

class MfaDriverFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new MfaDriverFactory;
    }

    public function testCreateTotp()
    {
        $configuration = new TotpConfiguration;

        $this->assertInstanceOf('Eloquent\Otis\Totp\TotpDriver', $this->factory->create($configuration));
    }

    public function testCreateHotp()
    {
        $configuration = new HotpConfiguration;

        $this->assertInstanceOf('Eloquent\Otis\Hotp\HotpDriver', $this->factory->create($configuration));
    }

    public function testCreateMotp()
    {
        $configuration = new MotpConfiguration;

        $this->assertInstanceOf('Eloquent\Otis\Motp\MotpDriver', $this->factory->create($configuration));
    }

    public function testCreateUnsupported()
    {
        $configuration = Phake::mock('Eloquent\Otis\Configuration\MfaConfigurationInterface');

        $this->setExpectedException('Eloquent\Otis\Exception\UnsupportedConfigurationException');
        $this->factory->create($configuration);
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Credentials;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Hotp\Credentials\HotpCredentials
 * @covers \Eloquent\Otis\Credentials\AbstractOtpCredentials
 */
class HotpCredentialsTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->parameters = new HotpCredentials('password');
    }

    public function testConstructor()
    {
        $this->assertSame('password', $this->parameters->password());
    }
}

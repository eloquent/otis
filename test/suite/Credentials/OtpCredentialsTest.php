<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Credentials;

use PHPUnit_Framework_TestCase;

class OtpCredentialsTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->credentials = new OtpCredentials('password');
    }

    public function testConstructor()
    {
        $this->assertSame('password', $this->credentials->password());
    }
}

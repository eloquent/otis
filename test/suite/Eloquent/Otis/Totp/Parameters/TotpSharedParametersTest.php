<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Parameters;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Totp\Parameters\TotpSharedParameters
 * @covers \Eloquent\Otis\Parameters\AbstractOtpSharedParameters
 */
class TotpSharedParametersTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->parameters = new TotpSharedParameters('secret');
    }

    public function testConstructor()
    {
        $this->assertSame('secret', $this->parameters->secret());
    }
}

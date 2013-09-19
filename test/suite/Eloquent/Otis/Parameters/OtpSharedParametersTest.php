<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

use PHPUnit_Framework_TestCase;

class OtpSharedParametersTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->parameters = new OtpSharedParameters('secret');
    }

    public function testConstructor()
    {
        $this->assertSame('secret', $this->parameters->secret());
    }
}

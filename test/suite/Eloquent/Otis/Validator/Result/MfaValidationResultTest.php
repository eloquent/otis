<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Result;

use PHPUnit_Framework_TestCase;

class MfaValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new MfaValidationResult(MfaValidationResult::VALID);

        $this->assertSame(MfaValidationResult::VALID, $result->type());
        $this->assertTrue($result->isSuccessful());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new MfaValidationResult(MfaValidationResult::INVALID_CREDENTIALS);

        $this->assertSame(MfaValidationResult::INVALID_CREDENTIALS, $result->type());
        $this->assertFalse($result->isSuccessful());
    }
}

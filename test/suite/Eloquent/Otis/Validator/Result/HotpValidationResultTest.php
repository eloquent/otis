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

/**
 * @covers \Eloquent\Otis\Validator\Result\HotpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractOtpValidationResult
 */
class HotpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new HotpValidationResult(ValidationResultType::VALID(), 111);

        $this->assertSame(ValidationResultType::VALID(), $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->counter());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new HotpValidationResult(ValidationResultType::INVALID_PASSWORD());

        $this->assertSame(ValidationResultType::INVALID_PASSWORD(), $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->counter());
    }

    public function testConstructorFailureSuccessButNoCounter()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidResultException');
        new HotpValidationResult(ValidationResultType::VALID());
    }

    public function testConstructorFailureUnsuccessfulWithCounter()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidResultException');
        new HotpValidationResult(ValidationResultType::INVALID_PASSWORD(), 111);
    }
}

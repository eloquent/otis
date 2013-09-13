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

class ValidationResultTypeTest extends PHPUnit_Framework_TestCase
{
    public function testEnumeration()
    {
        $this->assertSame(
            array(
                'VALID' => ValidationResultType::VALID(),
                'INVALID_PASSWORD' => ValidationResultType::INVALID_PASSWORD(),
                'PASSWORD_LENGTH_MISMATCH' => ValidationResultType::PASSWORD_LENGTH_MISMATCH(),
                'EMPTY_PASSWORD_SEQUENCE' => ValidationResultType::EMPTY_PASSWORD_SEQUENCE(),
            ),
            ValidationResultType::members()
        );
    }
}

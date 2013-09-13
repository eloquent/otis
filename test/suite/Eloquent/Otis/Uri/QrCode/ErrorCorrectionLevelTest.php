<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\QrCode;

use Eloquent\Liberator\Liberator;
use PHPUnit_Framework_TestCase;

class ErrorCorrectionLevelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Liberator::liberateClass('Eloquent\Enumeration\Multiton')->members = array();
    }

    public function testMembers()
    {
        $this->assertSame(
            array(
                'LOW' => ErrorCorrectionLevel::LOW(),
                'MEDIUM' => ErrorCorrectionLevel::MEDIUM(),
                'QUARTILE' => ErrorCorrectionLevel::QUARTILE(),
                'HIGH' => ErrorCorrectionLevel::HIGH(),
            ),
            ErrorCorrectionLevel::members()
        );
    }

    public function testLetterCode()
    {
        $this->assertSame('L', ErrorCorrectionLevel::LOW()->letterCode());
        $this->assertSame('M', ErrorCorrectionLevel::MEDIUM()->letterCode());
        $this->assertSame('Q', ErrorCorrectionLevel::QUARTILE()->letterCode());
        $this->assertSame('H', ErrorCorrectionLevel::HIGH()->letterCode());
    }

    public function testNumberCode()
    {
        $this->assertSame(1, ErrorCorrectionLevel::LOW()->numberCode());
        $this->assertSame(2, ErrorCorrectionLevel::MEDIUM()->numberCode());
        $this->assertSame(3, ErrorCorrectionLevel::QUARTILE()->numberCode());
        $this->assertSame(4, ErrorCorrectionLevel::HIGH()->numberCode());
    }
}

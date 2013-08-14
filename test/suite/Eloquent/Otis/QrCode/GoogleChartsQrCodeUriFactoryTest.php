<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\QrCode;

use PHPUnit_Framework_TestCase;

class GoogleChartsQrCodeUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new GoogleChartsQrCodeUriFactory;
    }

    public function createData()
    {
        //                          data       width height errorCorrection               margin encoding      expected
        return array(
            'All defaults' => array('foo bar', null, null,  null,                         null,  null,         'https://chart.googleapis.com/chart?cht=qr&chl=foo%20bar&chs=200x200&chld=|0'),
            'All options'  => array('foo bar', 111,  222,   ErrorCorrectionLevel::HIGH(), 3,     'ISO-8859-1', 'https://chart.googleapis.com/chart?cht=qr&chl=foo%20bar&chs=111x222&chld=H|3&choe=ISO-8859-1'),
        );
    }

    /**
     * @dataProvider createData
     */
    public function testCreate($data, $width, $height, $errorCorrection, $marging, $encoding, $expected)
    {
        $this->assertSame(
            $expected,
            $this->factory->create($data, $width, $height, $errorCorrection, $marging, $encoding)
        );
    }
}

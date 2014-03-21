<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\QrCode;

use PHPUnit_Framework_TestCase;

class GoogleChartsQrCodeUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new GoogleChartsQrCodeUriFactory;
    }

    public function createUriData()
    {
        //                          data       size  errorCorrection               expected
        return array(
            'All defaults' => array('foo bar', null, null,                         'https://chart.googleapis.com/chart?cht=qr&chs=250x250&chld=%7C0&chl=foo%20bar'),
            'All options'  => array('foo bar', 111,  ErrorCorrectionLevel::HIGH(), 'https://chart.googleapis.com/chart?cht=qr&chs=111x111&chld=H%7C0&chl=foo%20bar'),
        );
    }

    /**
     * @dataProvider createUriData
     */
    public function testCreateUri($data, $size, $errorCorrection, $expected)
    {
        $this->assertSame($expected, $this->factory->createUri($data, $size, $errorCorrection));
    }
}

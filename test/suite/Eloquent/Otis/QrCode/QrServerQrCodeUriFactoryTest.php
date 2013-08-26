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

class QrServerQrCodeUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new QrServerQrCodeUriFactory;
    }

    public function createUriData()
    {
        //                          data       size  errorCorrection               expected
        return array(
            'All defaults' => array('foo bar', null, null,                         'https://api.qrserver.com/v1/create-qr-code/?data=foo%20bar'),
            'All options'  => array('foo bar', 111,  ErrorCorrectionLevel::HIGH(), 'https://api.qrserver.com/v1/create-qr-code/?data=foo%20bar&size=111x111&ecc=H'),
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

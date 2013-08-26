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

class KaywaQrCodeUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new KaywaQrCodeUriFactory;
    }

    public function createUriData()
    {
        //                          data       size  errorCorrection               expected
        return array(
            'All defaults' => array('foo bar', null, null,                         'http://qrfree.kaywa.com/?d=foo%20bar&l=1'),
            'All options'  => array('foo bar', 111,  ErrorCorrectionLevel::HIGH(), 'http://qrfree.kaywa.com/?d=foo%20bar&s=111&l=4'),
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

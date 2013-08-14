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

/**
 * The interface implemented by QR code URI factories.
 */
interface QrCodeUriFactoryInterface
{
    /**
     * Create a URI that will generate a QR code with the supplied values.
     *
     * @param string                    $data            The data to encode.
     * @param integer|null              $width           The width of the QR code.
     * @param integer|null              $height          The height of the QR code.
     * @param ErrorCorrectionLevel|null $errorCorrection The level of error correction to use.
     * @param integer|null              $margin          The margin surrounding the QR code in rows.
     * @param string|null               $encoding        The string encoding to use for the data.
     *
     * @return string The QR code URI.
     */
    public function create(
        $data,
        $width = null,
        $height = null,
        ErrorCorrectionLevel $errorCorrection = null,
        $margin = null,
        $encoding = null
    );
}

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

/**
 * A QR code URI factory that produces Google Charts URIs.
 */
class GoogleChartsQrCodeUriFactory implements QrCodeUriFactoryInterface
{
    /**
     * Create a URI that will generate a QR code with the supplied values.
     *
     * @param string                    $data            The data to encode.
     * @param integer|null              $size            The size of the QR code. The units are implementation dependant.
     * @param ErrorCorrectionLevel|null $errorCorrection The level of error correction to use.
     *
     * @return string The QR code URI.
     */
    public function createUri(
        $data,
        $size = null,
        ErrorCorrectionLevel $errorCorrection = null
    ) {
        if (null === $size) {
            $size = 250;
        }
        if (null === $errorCorrection) {
            $errorCorrection = ErrorCorrectionLevel::LOW();
        }

        if (ErrorCorrectionLevel::LOW() === $errorCorrection) {
            $errorCorrectionString = '';
        } else {
            $errorCorrectionString = $errorCorrection->letterCode();
        }

        return sprintf(
            'https://chart.googleapis.com/chart?cht=qr' .
                '&chs=%sx%s&chld=%s%%7C0&chl=%s',
            rawurlencode($size),
            rawurlencode($size),
            $errorCorrectionString,
            rawurlencode($data)
        );
    }
}

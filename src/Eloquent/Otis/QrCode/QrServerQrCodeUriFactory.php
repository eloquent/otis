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
 * A QR code URI factory that produces URIs pointing to the QR-Server QR code
 * service.
 */
class QrServerQrCodeUriFactory implements QrCodeUriFactoryInterface
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

        $parameters = '';
        if (250 !== $size) {
            $parameters .= sprintf(
                '&size=%sx%s',
                rawurlencode($size),
                rawurlencode($size)
            );
        }
        if (ErrorCorrectionLevel::LOW() !== $errorCorrection) {
            $parameters .=
                '&ecc=' .
                rawurlencode($errorCorrection->letterCode());
        }

        return sprintf(
            'https://api.qrserver.com/v1/create-qr-code/?data=%s%s',
            rawurlencode($data),
            $parameters
        );
    }
}

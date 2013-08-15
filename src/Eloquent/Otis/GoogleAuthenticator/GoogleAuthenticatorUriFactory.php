<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\GoogleAuthenticator;

use Base32\Base32;

/**
 * Generates URIs for use with Google Authenticator, and other compatible OTP
 * apps.
 */
class GoogleAuthenticatorUriFactory implements
    GoogleAuthenticatorUriFactoryInterface
{
    /**
     * Create an HOTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param string       $secret        The shared secret.
     * @param string       $label         The label for the account.
     * @param string|null  $issuer        The issuer name.
     * @param integer|null $counter       The current counter value.
     * @param integer|null $digits        The number of password digits.
     * @param string|null  $algorithm     The algorithm to use.
     * @param boolean|null $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The HOTP URI.
     */
    public function createHotpUri(
        $secret,
        $label,
        $issuer = null,
        $counter = null,
        $digits = null,
        $algorithm = null,
        $issuerInLabel = null
    ) {
        return $this->createUri(
            'hotp',
            $secret,
            $label,
            $issuer,
            $counter,
            null,
            $digits,
            $algorithm,
            $issuerInLabel
        );
    }

    /**
     * Create a TOTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param string       $secret        The shared secret.
     * @param string       $label         The label for the account.
     * @param string|null  $issuer        The issuer name.
     * @param integer|null $window        The number of seconds each value is valid for.
     * @param integer|null $digits        The number of password digits.
     * @param string|null  $algorithm     The algorithm to use.
     * @param boolean|null $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The TOTP URI.
     */
    public function createTotpUri(
        $secret,
        $label,
        $issuer = null,
        $window = null,
        $digits = null,
        $algorithm = null,
        $issuerInLabel = null
    ) {
        return $this->createUri(
            'totp',
            $secret,
            $label,
            $issuer,
            null,
            $window,
            $digits,
            $algorithm,
            $issuerInLabel
        );
    }

    /**
     * @param string       $type
     * @param string       $secret
     * @param strgin       $label
     * @param string|null  $issuer
     * @param integer|null $counter
     * @param integer|null $window
     * @param integer|null $digits
     * @param string|null  $algorithm
     * @param boolean|null $issuerInLabel
     *
     * @return string
     */
    protected function createUri(
        $type,
        $secret,
        $label,
        $issuer = null,
        $counter = null,
        $window = null,
        $digits = null,
        $algorithm = null,
        $issuerInLabel = null
    ) {
        if (null === $counter && 'hotp' === $type) {
            $counter = 1;
        }
        if (null === $window && 'totp' === $type) {
            $window = 30;
        }
        if (null === $digits) {
            $digits = 6;
        }
        if (null === $algorithm) {
            $algorithm = 'SHA1';
        }
        if (null === $issuerInLabel) {
            $issuerInLabel = false;
        }

        $legacyIssuer = '';
        $parameters = '';

        if (null !== $counter && 1 !== $counter) {
            $parameters .= '&counter=' . rawurlencode($counter);
        }
        if (null !== $window && 30 !== $window) {
            $parameters .= '&period=' . rawurlencode($window);
        }
        if (null !== $digits && 6 !== $digits) {
            $parameters .= '&digits=' . rawurlencode($digits);
        }
        if (null !== $algorithm && 'SHA1' !== $algorithm) {
            $parameters .= '&algorithm=' . rawurlencode($algorithm);
        }
        if (null !== $issuer) {
            if ($issuerInLabel) {
                $legacyIssuer = rawurlencode($issuer) . ':';
            }

            $parameters .= '&issuer=' . rawurlencode($issuer);
        }

        return sprintf(
            'otpauth://%s/%s%s?secret=%s%s',
            rawurlencode($type),
            $legacyIssuer,
            rawurlencode($label),
            rawurlencode(Base32::encode($secret)),
            $parameters
        );
    }
}

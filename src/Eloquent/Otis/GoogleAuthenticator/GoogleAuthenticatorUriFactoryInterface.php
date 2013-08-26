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

/**
 * The interface implemented by Google Authenticator URI factories.
 */
interface GoogleAuthenticatorUriFactoryInterface
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
    );

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
    );
}

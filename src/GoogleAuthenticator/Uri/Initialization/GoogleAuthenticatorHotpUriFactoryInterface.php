<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\GoogleAuthenticator\Uri\Initialization;

use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;

/**
 * The interface implemented by Google Authenticator HOTP URI factories.
 */
interface GoogleAuthenticatorHotpUriFactoryInterface
{
    /**
     * Create a HOTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param HotpConfigurationInterface               $configuration The HOTP configuration.
     * @param CounterBasedOtpSharedParametersInterface $shared        The shared parameters.
     * @param string                                   $label         The label for the account.
     * @param string|null                              $issuer        The issuer name.
     * @param boolean|null                             $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The HOTP URI.
     */
    public function createHotp(
        HotpConfigurationInterface $configuration,
        CounterBasedOtpSharedParametersInterface $shared,
        $label,
        $issuer = null,
        $issuerInLabel = null
    );
}

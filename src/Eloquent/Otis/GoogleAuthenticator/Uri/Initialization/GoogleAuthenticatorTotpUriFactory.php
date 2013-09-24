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

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;

/**
 * Generates TOTP URIs for use with Google Authenticator, and other compatible
 * OTP apps.
 */
class GoogleAuthenticatorTotpUriFactory
    extends AbstractGoogleAuthenticatorUriFactory
    implements GoogleAuthenticatorTotpUriFactoryInterface
{
    /**
     * Create an initialization URI.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param MfaConfigurationInterface    $configuration The multi-factor authentication configuration.
     * @param MfaSharedParametersInterface $shared        The shared parameters.
     * @param string                       $label         The label for the account.
     * @param string|null                  $issuer        The issuer name.
     *
     * @return string The initialization URI.
     */
    public function create(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        $label,
        $issuer = null
    ) {
        return $this->createTotp($configuration, $shared, $label, $issuer);
    }

    /**
     * Create a TOTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param TotpConfigurationInterface            $configuration The TOTP configuration.
     * @param TimeBasedOtpSharedParametersInterface $shared        The shared parameters.
     * @param string                                $label         The label for the account.
     * @param string|null                           $issuer        The issuer name.
     * @param boolean|null                          $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The TOTP URI.
     */
    public function createTotp(
        TotpConfigurationInterface $configuration,
        TimeBasedOtpSharedParametersInterface $shared,
        $label,
        $issuer = null,
        $issuerInLabel = null
    ) {
        if (30 === $configuration->window()) {
            $parameters = '';
        } else {
            $parameters = '&period=' . rawurlencode($configuration->window());
        }

        return $this->buildUri(
            'totp',
            $parameters,
            $configuration,
            $shared,
            $label,
            $issuer,
            $issuerInLabel
        );
    }
}

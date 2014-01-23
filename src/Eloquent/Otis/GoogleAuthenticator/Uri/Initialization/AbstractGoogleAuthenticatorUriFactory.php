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

use Base32\Base32;
use Eloquent\Otis\Hotp\Configuration\HotpBasedConfigurationInterface;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;
use Eloquent\Otis\Uri\Initialization\InitializationUriFactoryInterface;

/**
 * An abstract base class for implementing  Google Authenticator OTP URI
 * factories.
 */
abstract class AbstractGoogleAuthenticatorUriFactory implements
    InitializationUriFactoryInterface
{
    /**
     * Create an OTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param string                          $type          The otp type identifier.
     * @param string                          $parameters    Additional URI parameters.
     * @param HotpBasedConfigurationInterface $configuration The OTP configuration.
     * @param OtpSharedParametersInterface    $shared        The shared parameters.
     * @param string                          $label         The label for the account.
     * @param string|null                     $issuer        The issuer name.
     * @param boolean|null                    $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The OTP URI.
     */
    protected function buildUri(
        $type,
        $parameters,
        HotpBasedConfigurationInterface $configuration,
        OtpSharedParametersInterface $shared,
        $label,
        $issuer = null,
        $issuerInLabel = null
    ) {
        if (null === $issuerInLabel) {
            $issuerInLabel = false;
        }

        if (6 !== $configuration->digits()) {
            $parameters .= '&digits=' . rawurlencode($configuration->digits());
        }

        if (HotpHashAlgorithm::SHA1() !== $configuration->algorithm()) {
            $parameters .= '&algorithm=' . rawurlencode(
                $configuration->algorithm()->value()
            );
        }

        $legacyIssuer = '';
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
            rawurlencode(Base32::encode($shared->secret())),
            $parameters
        );
    }
}

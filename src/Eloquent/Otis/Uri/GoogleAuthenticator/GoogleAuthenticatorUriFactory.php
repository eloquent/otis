<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\GoogleAuthenticator;

use Base32\Base32;
use Eloquent\Otis\Hotp\Configuration\HotpBasedConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;

/**
 * Generates URIs for use with Google Authenticator, and other compatible OTP
 * apps.
 */
class GoogleAuthenticatorUriFactory implements
    GoogleAuthenticatorUriFactoryInterface
{
    /**
     * Create a TOTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param TotpConfigurationInterface $configuration The TOTP configuration.
     * @param string                     $secret        The shared secret.
     * @param string                     $label         The label for the account.
     * @param string|null                $issuer        The issuer name.
     * @param boolean|null               $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The TOTP URI.
     */
    public function createTotpUri(
        TotpConfigurationInterface $configuration,
        $secret,
        $label,
        $issuer = null,
        $issuerInLabel = null
    ) {
        if (30 === $configuration->window()) {
            $parameters = '';
        } else {
            $parameters = '&period=' . rawurlencode($configuration->window());
        }

        return $this->createUri(
            'totp',
            $parameters,
            $configuration,
            $secret,
            $label,
            $issuer,
            $issuerInLabel
        );
    }

    /**
     * Create a HOTP URI for use with Google Authenticator.
     *
     * Note that this is not a URI for the QR code used by Google Authenticator.
     * The URI produced by this method is used as the actual content of the QR
     * code, and follows a special set of conventions understood by Google
     * Authenticator, and other OTP apps.
     *
     * @param HotpConfigurationInterface $configuration The HOTP configuration.
     * @param string                     $secret        The shared secret.
     * @param string                     $label         The label for the account.
     * @param integer|null               $counter       The current counter value.
     * @param string|null                $issuer        The issuer name.
     * @param boolean|null               $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The HOTP URI.
     */
    public function createHotpUri(
        HotpConfigurationInterface $configuration,
        $secret,
        $label,
        $counter = null,
        $issuer = null,
        $issuerInLabel = null
    ) {
        if (null === $counter || 1 === $counter) {
            $parameters = '';
        } else {
            $parameters = '&counter=' . rawurlencode($counter);
        }

        return $this->createUri(
            'hotp',
            $parameters,
            $configuration,
            $secret,
            $label,
            $issuer,
            $issuerInLabel
        );
    }

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
     * @param string                          $secret        The shared secret.
     * @param string                          $label         The label for the account.
     * @param string|null                     $issuer        The issuer name.
     * @param boolean|null                    $issuerInLabel True if legacy issuer support should be enabled by prefixing the label with the issuer name.
     *
     * @return string The OTP URI.
     */
    protected function createUri(
        $type,
        $parameters,
        HotpBasedConfigurationInterface $configuration,
        $secret,
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
            rawurlencode(Base32::encode($secret)),
            $parameters
        );
    }
}

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
use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Exception\UnsupportedArgumentsException;
use Eloquent\Otis\Hotp\Configuration\HotpBasedConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Uri\Initialization\InitializationUriFactoryInterface;

/**
 * Generates URIs for use with Google Authenticator, and other compatible OTP
 * apps.
 */
class GoogleAuthenticatorUriFactory implements
    InitializationUriFactoryInterface,
    GoogleAuthenticatorUriFactoryInterface
{
    /**
     * Returns true if this initialization URI factory supports the supplied
     * combination of configuration and shared parameters.
     *
     * @param MfaConfigurationInterface    $configuration The multi-factor authentication configuration.
     * @param MfaSharedParametersInterface $shared        The shared parameters.
     *
     * @return boolean True if the configuration and shared parameters are supported.
     */
    public function supports(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared
    ) {
        if (
            $configuration instanceof TotpConfigurationInterface &&
            $shared instanceof TimeBasedOtpSharedParametersInterface
        ) {
            return true;
        }

        if (
            $configuration instanceof HotpConfigurationInterface &&
            $shared instanceof CounterBasedOtpSharedParametersInterface
        ) {
            return true;
        }

        return false;
    }

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
     * @return string                        The initialization URI.
     * @throws UnsupportedArgumentsException If the combination of configuration and shared parameters is not supported.
     */
    public function create(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        $label,
        $issuer = null
    ) {
        if (
            $configuration instanceof TotpConfigurationInterface &&
            $shared instanceof TimeBasedOtpSharedParametersInterface
        ) {
            return $this->createTotp($configuration, $shared, $label, $issuer);
        }

        if (
            $configuration instanceof HotpConfigurationInterface &&
            $shared instanceof CounterBasedOtpSharedParametersInterface
        ) {
            return $this->createHotp($configuration, $shared, $label, $issuer);
        }

        throw new UnsupportedArgumentsException;
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
    ) {
        if (1 === $shared->counter()) {
            $parameters = '';
        } else {
            $parameters = '&counter=' . rawurlencode($shared->counter());
        }

        return $this->buildUri(
            'hotp',
            $parameters,
            $configuration,
            $shared,
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

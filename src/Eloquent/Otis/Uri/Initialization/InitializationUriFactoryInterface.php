<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\Initialization;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Validator\Exception\UnsupportedArgumentsException;

/**
 * The interface implemented by initialization URI factories.
 */
interface InitializationUriFactoryInterface
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
    );

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
    );
}

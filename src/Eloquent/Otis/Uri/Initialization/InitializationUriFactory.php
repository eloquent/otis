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
use Eloquent\Otis\Exception\UnsupportedArgumentsException;
use Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\GoogleAuthenticatorUriFactory;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;

/**
 * Creates initialization URIs.
 */
class InitializationUriFactory implements InitializationUriFactoryInterface
{
    /**
     * Construct a new initialization URI factory.
     *
     * @param array<InitializationUriFactoryInterface>|null $factories The factories to aggregate.
     */
    public function __construct(array $factories = null)
    {
        if (null === $factories) {
            $factories = array(
                new GoogleAuthenticatorUriFactory,
            );
        }

        $this->factories = $factories;
    }

    /**
     * Get the aggregated factories.
     *
     * @return array<InitializationUriFactoryInterface> The aggregated factories.
     */
    public function factories()
    {
        return $this->factories;
    }

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
        foreach ($this->factories() as $factory) {
            if ($factory->supports($configuration, $shared)) {
                return true;
            }
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
        foreach ($this->factories() as $factory) {
            if ($factory->supports($configuration, $shared)) {
                return $factory->create(
                    $configuration,
                    $shared,
                    $label,
                    $issuer
                );
            }
        }

        throw new UnsupportedArgumentsException;
    }

    private $factories;
}

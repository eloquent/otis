<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Exception;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Credentials\MfaCredentialsInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Exception;

/**
 * An unsupported combination of multi-factor authentication configuration,
 * shared parameters, and credentials was supplied.
 */
class UnsupportedMfaCombinationException extends Exception
{
    /**
     * Construct a new unsupported multi-factor authentication combination
     * exception.
     *
     * @param MfaConfigurationInterface    $configuration The configuration.
     * @param MfaSharedParametersInterface $shared        The shared parameters.
     * @param MfaCredentialsInterface      $credentials   The credentials.
     * @param Exception|null               $previous      The cause, if available.
     */
    public function __construct(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials,
        Exception $previous = null
    ) {
        $this->configuration = $configuration;
        $this->shared = $shared;
        $this->credentials = $credentials;

        parent::__construct(
            sprintf(
                'Unsupported combination of multi-factor authentication '.
                    'configuration, shared parameters, and credentials ' .
                    '(%s, %s and %s).',
                var_export(get_class($configuration), true),
                var_export(get_class($shared), true),
                var_export(get_class($credentials), true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the configuration.
     *
     * @return MfaConfigurationInterface The configuration.
     */
    public function configuration()
    {
        return $this->configuration;
    }

    /**
     * Get the shared parameters.
     *
     * @return MfaSharedParametersInterface The shared parameters.
     */
    public function shared()
    {
        return $this->shared;
    }

    /**
     * Get the credentials.
     *
     * @return MfaCredentialsInterface The credentials.
     */
    public function credentials()
    {
        return $this->credentials;
    }

    private $configuration;
    private $shared;
    private $credentials;
}

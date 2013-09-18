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
use Eloquent\Otis\Validator\Parameters\MfaParametersInterface;
use Exception;

/**
 * An unsupported combination of multi-factor authentication configuration and
 * parameters was supplied.
 */
class UnsupportedMfaCombinationException extends Exception
{
    /**
     * Construct a new unsupported multi-factor authentication combination
     * exception.
     *
     * @param MfaConfigurationInterface $configuration The supplied configuration.
     * @param MfaParametersInterface    $parameters    The supplied parameters.
     * @param Exception|null            $previous      The cause, if available.
     */
    public function __construct(
        MfaConfigurationInterface $configuration,
        MfaParametersInterface $parameters,
        Exception $previous = null
    ) {
        $this->configuration = $configuration;
        $this->parameters = $parameters;

        parent::__construct(
            sprintf(
                'Unsupported multi-factor configuration and parameters ' .
                    'combination (%s and %s).',
                var_export(get_class($configuration), true),
                var_export(get_class($parameters), true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the supplied configuration.
     *
     * @return MfaConfigurationInterface The supplied configuration.
     */
    public function configuration()
    {
        return $this->configuration;
    }

    /**
     * Get the supplied parameters.
     *
     * @return MfaParametersInterface The supplied parameters.
     */
    public function parameters()
    {
        return $this->parameters;
    }

    private $configuration;
    private $parameters;
}

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
use Exception;

/**
 * An unsupported multi-factor authentication configuration was supplied.
 */
class UnsupportedMfaConfigurationException extends Exception
{
    /**
     * Construct a new unsupported multi-factor authentication configuration
     * exception.
     *
     * @param MfaConfigurationInterface $configuration The supplied configuration.
     * @param Exception|null            $previous      The cause, if available.
     */
    public function __construct(
        MfaConfigurationInterface $configuration,
        Exception $previous = null
    ) {
        $this->configuration = $configuration;

        parent::__construct(
            sprintf(
                'Multi-factor authentication configuration of type %s '.
                    'is not supported.',
                var_export(get_class($configuration), true)
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

    private $configuration;
}

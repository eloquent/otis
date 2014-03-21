<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Exception;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Exception;

/**
 * The supplied configuration is not supported.
 */
class UnsupportedConfigurationException extends Exception
{
    /**
     * Construct a new unsupported configuration exception.
     *
     * @param MfaConfigurationInterface $configuration The configuration.
     * @param Exception|null            $previous      The cause, if available.
     */
    public function __construct(
        MfaConfigurationInterface $configuration,
        Exception $previous = null
    ) {
        $this->configuration = $configuration;

        parent::__construct(
            sprintf(
                'Unsupported configuration of type %s supplied.',
                var_export(get_class($configuration), true)
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

    private $configuration;
}

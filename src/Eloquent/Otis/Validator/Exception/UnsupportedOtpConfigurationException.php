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

use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Exception;

/**
 * An unsupported OTP configuration was supplied.
 */
class UnsupportedOtpConfigurationException extends Exception
{
    /**
     * Construct a new unsupported OTP configuration exception.
     *
     * @param OtpConfigurationInterface $configuration The supplied configuration.
     * @param Exception|null            $previous      The cause, if available.
     */
    public function __construct(
        OtpConfigurationInterface $configuration,
        Exception $previous = null
    ) {
        $this->configuration = $configuration;

        parent::__construct(
            sprintf(
                'OTP configuration of type %s is not supported.',
                var_export(get_class($configuration), true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the supplied configuration.
     *
     * @return OtpConfigurationInterface The supplied configuration.
     */
    public function configuration()
    {
        return $this->configuration;
    }

    private $configuration;
}

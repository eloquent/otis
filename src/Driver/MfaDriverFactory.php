<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Driver;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Exception\UnsupportedConfigurationException;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Hotp\HotpDriver;
use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\MotpDriver;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Totp\TotpDriver;

/**
 * Creates multi-factor authentication drivers.
 */
class MfaDriverFactory implements MfaDriverFactoryInterface
{
    /**
     * Create an appropriate multi-factor authentication driver for the supplied
     * configuration.
     *
     * @param MfaConfigurationInterface $configuration The configuration.
     *
     * @return MfaDriverInterface                The driver.
     * @throws UnsupportedConfigurationException If the supplied configuration is not supported.
     */
    public function create(MfaConfigurationInterface $configuration)
    {
        if ($configuration instanceof TotpConfigurationInterface) {
            return new TotpDriver;
        }
        if ($configuration instanceof HotpConfigurationInterface) {
            return new HotpDriver;
        }
        if ($configuration instanceof MotpConfigurationInterface) {
            return new MotpDriver;
        }

        throw new UnsupportedConfigurationException($configuration);
    }
}

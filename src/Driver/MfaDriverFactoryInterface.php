<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Driver;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Exception\UnsupportedConfigurationException;
/**
 * The interface implemented by multi-factor authentication driver factories.
 */
interface MfaDriverFactoryInterface
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
    public function create(MfaConfigurationInterface $configuration);
}

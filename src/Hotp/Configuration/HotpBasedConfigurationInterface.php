<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Configuration;

use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;

/**
 * The interface implemented by one-time password authentication configurations
 * based upon HOTP.
 */
interface HotpBasedConfigurationInterface extends OtpConfigurationInterface
{
    /**
     * Get the underlying algorithm name.
     *
     * @return HotpHashAlgorithm The algorithm name.
     */
    public function algorithm();
}

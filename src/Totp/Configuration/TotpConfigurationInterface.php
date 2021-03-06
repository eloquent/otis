<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Configuration;

use Eloquent\Otis\Configuration\TimeBasedOtpConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpBasedConfigurationInterface;

/**
 * The interface implemented by TOTP configurations.
 */
interface TotpConfigurationInterface extends
    TimeBasedOtpConfigurationInterface,
    HotpBasedConfigurationInterface
{
}

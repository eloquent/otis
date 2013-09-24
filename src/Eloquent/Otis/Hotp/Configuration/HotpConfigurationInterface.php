<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Configuration;

use Eloquent\Otis\Configuration\CounterBasedOtpConfigurationInterface;

/**
 * The interface implemented by HOTP configurations.
 */
interface HotpConfigurationInterface extends
    CounterBasedOtpConfigurationInterface,
    HotpBasedConfigurationInterface
{
}

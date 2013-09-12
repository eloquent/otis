<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

/**
 * The interface implemented by TOTP configurations.
 */
interface TotpConfigurationInterface extends OtpConfigurationInterface
{
    /**
     * Get the number of seconds each token is valid for.
     *
     * @return integer The number of seconds each token is valid for.
     */
    public function window();
}

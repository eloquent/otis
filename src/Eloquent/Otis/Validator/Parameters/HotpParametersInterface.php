<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Parameters;

/**
 * The interface implemented by HOTP validation parameters.
 */
interface HotpParametersInterface extends OtpParametersInterface
{
    /**
     * Get the current counter value.
     *
     * @return integer The current counter value.
     */
    public function counter();
}

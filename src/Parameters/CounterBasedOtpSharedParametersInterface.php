<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

/**
 * The interface implemented by counter-based one-time password shared
 * parameters.
 */
interface CounterBasedOtpSharedParametersInterface extends
    OtpSharedParametersInterface
{
    /**
     * Set the counter value.
     *
     * @param integer $counter The counter value.
     */
    public function setCounter($counter);

    /**
     * Get the counter value.
     *
     * @return integer The counter value.
     */
    public function counter();
}

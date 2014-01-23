<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

/**
 * The interface implemented by one-time password shared parameters.
 */
interface OtpSharedParametersInterface extends MfaSharedParametersInterface
{
    /**
     * Get the shared secret.
     *
     * @return string The shared secret.
     */
    public function secret();
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Generator;

/**
 * The interface implemented by mOTP generators.
 */
interface MotpGeneratorInterface
{
    /**
     * Generate an mOTP value.
     *
     * @link http://motp.sourceforge.net/#1.1
     *
     * @param string       $secret The shared secret.
     * @param string       $pin    The PIN.
     * @param integer|null $time   The Unix timestamp to generate the password for.
     *
     * @return string The generated mOTP value.
     */
    public function generate($secret, $pin, $time = null);
}

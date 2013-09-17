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

use Icecave\Isolator\Isolator;

/**
 * Generates mOTP values.
 */
class MotpGenerator implements MotpGeneratorInterface
{
    /**
     * Construct a new mOTP generator.
     *
     * @param Isolator|null $isolator The isolator to use.
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->isolator = Isolator::get($isolator);
    }

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
    public function generate($secret, $pin, $time = null)
    {
        if (null === $time) {
            $time = $this->isolator()->time();
        }

        return substr(
            md5(strval(intval($time / 10)) . bin2hex($secret) . $pin),
            0,
            6
        );
    }

    /**
     * Get the isolator.
     *
     * @return Isolator The isolator.
     */
    protected function isolator()
    {
        return $this->isolator;
    }

    private $isolator;
}

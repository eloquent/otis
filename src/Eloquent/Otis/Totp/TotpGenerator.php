<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp;

use Eloquent\Otis\Hotp\HotpGenerator;
use Eloquent\Otis\Hotp\HotpGeneratorInterface;
use Eloquent\Otis\Hotp\HotpValue;
use Icecave\Isolator\Isolator;

/**
 * Generates TOTP values.
 */
class TotpGenerator implements TotpGeneratorInterface
{
    /**
     * Construct a new TOTP generator.
     *
     * @param HotpGeneratorInterface|null $generator The HOTP generator to use.
     * @param Isolator|null               $isolator  The isolator to use.
     */
    public function __construct(
        HotpGeneratorInterface $generator = null,
        Isolator $isolator = null
    ) {
        if (null === $generator) {
            $generator = new HotpGenerator;
        }

        $this->generator = $generator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Get the HOTP generator.
     *
     * @return HotpGeneratorInterface The generator.
     */
    public function generator()
    {
        return $this->generator;
    }

    /**
     * Generate a TOTP value.
     *
     * @link http://tools.ietf.org/html/rfc6238#section-4
     *
     * @param string       $secret The shared secret.
     * @param integer|null $window The number of seconds each value is valid for.
     * @param integer|null $time   The Unix timestamp to generate the password for.
     *
     * @return HotpValue
     */
    public function generate($secret, $window = null, $time = null)
    {
        if (null === $window) {
            $window = 30;
        }
        if (null === $time) {
            $time = $this->isolator()->time();
        }

        return $this->generator()->generate(
            $secret,
            intval(floor($time / $window))
        );
    }

    /**
     * @return Isolator
     */
    protected function isolator()
    {
        return $this->isolator;
    }

    private $generator;
    private $isolator;
}

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

use Eloquent\Otis\Hotp\Exception\InvalidPasswordLengthException;
use Icecave\Isolator\Isolator;

/**
 * Validates TOTP passwords.
 */
class TotpValidator implements TotpValidatorInterface
{
    /**
     * Construct a new TOTP validator.
     *
     * @param TotpGeneratorInterface|null $generator The generator to use.
     * @param Isolator|null               $isolator  The isolator to use.
     */
    public function __construct(
        TotpGeneratorInterface $generator = null,
        Isolator $isolator = null
    ) {
        if (null === $generator) {
            $generator = new TotpGenerator;
        }

        $this->generator = $generator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Get the generator.
     *
     * @return TotpGeneratorInterface The generator.
     */
    public function generator()
    {
        return $this->generator;
    }

    /**
     * Validate a TOTP password.
     *
     * @param string       $password      The password to validate.
     * @param string       $secret        The TOTP secret.
     * @param integer|null $digits        The number of password digits.
     * @param integer|null $window        The number of seconds each token is valid for.
     * @param integer|null $pastWindows   The number of past windows to check.
     * @param integer|null $futureWindows The number of future windows to check.
     * @param integer|null &$driftWindows Will be set to the number of windows of clock drift.
     *
     * @return boolean True if the password is valid.
     */
    public function validate(
        $password,
        $secret,
        $digits = null,
        $window = null,
        $pastWindows = null,
        $futureWindows = null,
        &$driftWindows = null
    ) {
        $driftWindows = null;

        if (null === $digits) {
            $digits = 6;
        }
        if (null === $window) {
            $window = 30;
        }
        if (null === $pastWindows) {
            $pastWindows = 1;
        }
        if (null === $futureWindows) {
            $futureWindows = 1;
        }

        if (strlen($password) !== $digits) {
            return false;
        }

        $time = $this->isolator()->time();

        for ($i = -$pastWindows; $i <= $futureWindows; ++$i) {
            $result = $this->generator()->generate(
                $secret,
                $window,
                $time + ($i * $window)
            );

            try {
                $thisPassword = $result->string($digits);
            } catch (InvalidPasswordLengthException $e) {
                return false;
            }

            if ($thisPassword === $password) {
                $driftWindows = $i;

                return true;
            }
        }

        return false;
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

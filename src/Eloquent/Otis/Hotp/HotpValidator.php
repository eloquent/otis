<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp;

/**
 * Validates HOTP passwords.
 */
class HotpValidator implements HotpValidatorInterface
{
    /**
     * Construct a new HOTP validator.
     *
     * @param HotpGeneratorInterface|null $generator The generator to use.
     */
    public function __construct(HotpGeneratorInterface $generator = null)
    {
        if (null === $generator) {
            $generator = new HotpGenerator;
        }

        $this->generator = $generator;
    }

    /**
     * Get the generator.
     *
     * @return HotpGeneratorInterface The generator.
     */
    public function generator()
    {
        return $this->generator;
    }

    /**
     * Validate an HOTP password.
     *
     * @param string       $password       The password to validate.
     * @param string       $secret         The HOTP secret.
     * @param integer      $currentCounter The current counter value.
     * @param integer|null &$newCounter    Will be set to the new counter value.
     * @param integer|null $digits         The number of password digits.
     * @param integer|null $window         The amount of counter increments to search through for a match.
     *
     * @return boolean True if the password is valid.
     */
    public function validate(
        $password,
        $secret,
        $currentCounter,
        &$newCounter = null,
        $digits = null,
        $window = null
    ) {
        $newCounter = $currentCounter;

        if (null === $digits) {
            $digits = 6;
        }
        if (null === $window) {
            $window = 0;
        }

        for (
            $counter = $currentCounter;
            $counter <= $currentCounter + $window;
            ++$counter
        ) {
            $value = $this->generator()->generate($secret, $counter);

            try {
                $thisPassword = $value->string($digits);
            } catch (Exception\InvalidPasswordLengthException $e) {
                return false;
            }

            if ($thisPassword === $password) {
                $newCounter = $counter + 1;

                return true;
            }
        }

        return false;
    }

    /**
     * Validate a sequence of HOTP passwords.
     *
     * @param array<string> $passwords      The password sequence to validate.
     * @param string        $secret         The HOTP secret.
     * @param integer       $currentCounter The current counter value.
     * @param integer|null  &$newCounter    Will be set to the new counter value.
     * @param integer|null  $digits         The number of password digits.
     * @param integer|null  $window         The amount of counter increments to search through for a match.
     *
     * @return boolean True if the password is valid.
     */
    public function validateSequence(
        array $passwords,
        $secret,
        $currentCounter,
        &$newCounter = null,
        $digits = null,
        $window = null
    ) {
        $newCounter = $currentCounter;

        if (count($passwords) < 1) {
            return false;
        }

        if (
            $this->validate(
                array_shift($passwords),
                $secret,
                $currentCounter,
                $counter,
                $digits,
                $window
            )
        ) {
            foreach ($passwords as $password) {
                if (
                    !$this->validate(
                        $password,
                        $secret,
                        $counter,
                        $counter,
                        $digits
                    )
                ) {
                    return false;
                }
            }

            $newCounter = $counter;

            return true;
        }

        return false;
    }

    private $generator;
}

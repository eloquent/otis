<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator;

use Eloquent\Otis\Configuration\HotpConfiguration;
use Eloquent\Otis\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Generator\Exception\InvalidPasswordLengthException;
use Eloquent\Otis\Generator\HotpGenerator;
use Eloquent\Otis\Generator\HotpGeneratorInterface;

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
     * @param HotpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\HotpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validate(
        HotpConfigurationInterface $configuration,
        Parameters\HotpParametersInterface $parameters
    ) {
        if (strlen($parameters->password()) !== $configuration->digits()) {
            return new Result\HotpValidationResult(
                Result\ValidationResultType::PASSWORD_LENGTH_MISMATCH()
            );
        }

        for (
            $counter = $parameters->counter();
            $counter <= $parameters->counter() + $configuration->window();
            ++$counter
        ) {
            $value = $this->generator()->generate(
                $parameters->secret(),
                $counter,
                $configuration->algorithm()
            );

            if (
                $parameters->password() === $value->string(
                    $configuration->digits()
                )
            ) {
                return new Result\HotpValidationResult(
                    Result\ValidationResultType::VALID(),
                    $counter + 1
                );
            }
        }

        return new Result\HotpValidationResult(
            Result\ValidationResultType::INVALID_PASSWORD()
        );
    }

    /**
     * Validate a sequence of HOTP passwords.
     *
     * @param HotpConfigurationInterface $configuration The configuration to use for validation.
     * @param string                     $secret        The shared secret.
     * @param array<string>              $passwords     The password sequence to validate.
     * @param integer                    $counter       The current counter value.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validateSequence(
        HotpConfigurationInterface $configuration,
        $secret,
        array $passwords,
        $counter
    ) {
        if (count($passwords) < 1) {
            return new Result\HotpValidationResult(
                Result\ValidationResultType::EMPTY_PASSWORD_SEQUENCE()
            );
        }

        $first = true;
        foreach ($passwords as $password) {
            if ($first) {
                $window = $configuration->window();
            } else {
                $window = 0;
            }

            $result = $this->validate(
                new HotpConfiguration(
                    $configuration->digits(),
                    $window,
                    $configuration->initialCounter(),
                    $configuration->secretLength(),
                    $configuration->algorithm()
                ),
                new Parameters\HotpParameters($secret, $password, $counter)
            );

            if (!$result->isSuccessful()) {
                break;
            }

            $counter = $result->counter();
            $first = false;
        }

        return $result;
    }

    private $generator;
}

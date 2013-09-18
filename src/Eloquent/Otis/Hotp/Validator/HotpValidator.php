<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Validator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Hotp\Generator\HotpGenerator;
use Eloquent\Otis\Hotp\Generator\HotpGeneratorInterface;
use Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\Parameters\MfaParametersInterface;
use Eloquent\Otis\Validator\Result\MfaValidationResultInterface;

/**
 * Validates HOTP passwords.
 */
class HotpValidator implements MfaValidatorInterface, HotpValidatorInterface
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
     * Returns true if this validator supports the supplied combination of
     * configuration and parameters.
     *
     * @param MfaConfigurationInterface $configuration The configuration to use for validation.
     * @param MfaParametersInterface    $parameters    The parameters to validate.
     *
     * @return boolean True if this validator supports the supplied combination.
     */
    public function supports(
        MfaConfigurationInterface $configuration,
        MfaParametersInterface $parameters
    ) {
        return $configuration instanceof HotpConfigurationInterface &&
            $parameters instanceof Parameters\HotpParametersInterface;
    }

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\MfaParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\MfaValidationResultInterface          The validation result.
     * @throws Exception\UnsupportedMfaCombinationException If the combination of configuration and parameters is not supported.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        MfaParametersInterface $parameters
    ) {
        if (!$this->supports($configuration, $parameters)) {
            throw new UnsupportedMfaCombinationException(
                $configuration,
                $parameters
            );
        }

        return $this->validateHotp($configuration, $parameters);
    }

    /**
     * Validate an HOTP password.
     *
     * @param HotpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\HotpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validateHotp(
        HotpConfigurationInterface $configuration,
        Parameters\HotpParametersInterface $parameters
    ) {
        if (strlen($parameters->password()) !== $configuration->digits()) {
            return new Result\HotpValidationResult(
                Result\HotpValidationResult::PASSWORD_LENGTH_MISMATCH
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
                    Result\HotpValidationResult::VALID,
                    $counter + 1
                );
            }
        }

        return new Result\HotpValidationResult(
            Result\HotpValidationResult::INVALID_PASSWORD
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
    public function validateHotpSequence(
        HotpConfigurationInterface $configuration,
        $secret,
        array $passwords,
        $counter
    ) {
        if (count($passwords) < 1) {
            return new Result\HotpValidationResult(
                Result\HotpValidationResult::EMPTY_PASSWORD_SEQUENCE
            );
        }

        $first = true;
        foreach ($passwords as $password) {
            if ($first) {
                $window = $configuration->window();
            } else {
                $window = 0;
            }

            $result = $this->validateHotp(
                new HotpConfiguration(
                    $configuration->digits(),
                    $window,
                    $configuration->initialCounter(),
                    $configuration->secretLength(),
                    $configuration->algorithm()
                ),
                new Parameters\HotpParameters($secret, $counter, $password)
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

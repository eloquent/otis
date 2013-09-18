<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Validator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Totp\Generator\TotpGenerator;
use Eloquent\Otis\Totp\Generator\TotpGeneratorInterface;
use Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\Parameters\MfaParametersInterface;
use Icecave\Isolator\Isolator;

/**
 * Validates TOTP passwords.
 */
class TotpValidator implements MfaValidatorInterface, TotpValidatorInterface
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
        return $configuration instanceof TotpConfigurationInterface &&
            $parameters instanceof Parameters\TotpParametersInterface;
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

        return $this->validateTotp($configuration, $parameters);
    }

    /**
     * Validate a TOTP password.
     *
     * @param TotpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\TotpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\TotpValidationResultInterface The validation result.
     */
    public function validateTotp(
        TotpConfigurationInterface $configuration,
        Parameters\TotpParametersInterface $parameters
    ) {
        if (strlen($parameters->password()) !== $configuration->digits()) {
            return new Result\TotpValidationResult(
                Result\TotpValidationResult::PASSWORD_LENGTH_MISMATCH
            );
        }

        $time = $this->isolator()->time();

        for (
            $i = -$configuration->pastWindows();
            $i <= $configuration->futureWindows();
            ++$i
        ) {
            $value = $this->generator()->generate(
                $parameters->secret(),
                $configuration->window(),
                $time + ($i * $configuration->window()),
                $configuration->algorithm()
            );

            if (
                $parameters->password() === $value->string(
                    $configuration->digits()
                )
            ) {
                return new Result\TotpValidationResult(
                    Result\TotpValidationResult::VALID,
                    $i
                );
            }
        }

        return new Result\TotpValidationResult(
            Result\TotpValidationResult::INVALID_PASSWORD
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

    private $generator;
    private $isolator;
}

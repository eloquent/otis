<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Generator\MotpGenerator;
use Eloquent\Otis\Motp\Generator\MotpGeneratorInterface;
use Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\Parameters\MfaParametersInterface;
use Icecave\Isolator\Isolator;

/**
 * Validates mOTP passwords.
 */
class MotpValidator implements MfaValidatorInterface, MotpValidatorInterface
{
    /**
     * Construct a new mOTP validator.
     *
     * @param MotpGeneratorInterface|null $generator The generator to use.
     * @param Isolator|null               $isolator  The isolator to use.
     */
    public function __construct(
        MotpGeneratorInterface $generator = null,
        Isolator $isolator = null
    ) {
        if (null === $generator) {
            $generator = new MotpGenerator;
        }

        $this->generator = $generator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Get the generator.
     *
     * @return MotpGeneratorInterface The generator.
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
        return $configuration instanceof MotpConfigurationInterface &&
            $parameters instanceof Parameters\MotpParametersInterface;
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

        return $this->validateMotp($configuration, $parameters);
    }

    /**
     * Validate an mOTP password.
     *
     * @param MotpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\MotpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\MotpValidationResultInterface The validation result.
     */
    public function validateMotp(
        MotpConfigurationInterface $configuration,
        Parameters\MotpParametersInterface $parameters
    ) {
        if (strlen($parameters->password()) !== 6) {
            return new Result\MotpValidationResult(
                Result\MotpValidationResult::PASSWORD_LENGTH_MISMATCH
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
                $parameters->pin(),
                $time + ($i * 10)
            );

            if ($parameters->password() === $value) {
                return new Result\MotpValidationResult(
                    Result\MotpValidationResult::VALID,
                    $i
                );
            }
        }

        return new Result\MotpValidationResult(
            Result\MotpValidationResult::INVALID_PASSWORD
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

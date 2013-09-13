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

use Eloquent\Otis\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Generator\Exception\InvalidPasswordLengthException;
use Eloquent\Otis\Generator\TotpGenerator;
use Eloquent\Otis\Generator\TotpGeneratorInterface;
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
     * @param TotpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\TotpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\TotpValidationResultInterface The validation result.
     */
    public function validate(
        TotpConfigurationInterface $configuration,
        Parameters\TotpParametersInterface $parameters
    ) {
        if (strlen($parameters->password()) !== $configuration->digits()) {
            return new Result\TotpValidationResult(
                Result\ValidationResultType::PASSWORD_LENGTH_MISMATCH()
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
                    Result\ValidationResultType::VALID(),
                    $i
                );
            }
        }

        return new Result\TotpValidationResult(
            Result\ValidationResultType::INVALID_PASSWORD()
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

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

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Hotp\Validator\HotpValidator;
use Eloquent\Otis\Hotp\Validator\HotpValidatorInterface;
use Eloquent\Otis\Hotp\Validator\Parameters\HotpParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Totp\Validator\Parameters\TotpParametersInterface;
use Eloquent\Otis\Totp\Validator\TotpValidator;
use Eloquent\Otis\Totp\Validator\TotpValidatorInterface;

/**
 * A generic multi-factor authentication validator.
 */
class MfaValidator implements MfaValidatorInterface
{
    /**
     * Construct a new multi-factor authentication validator.
     *
     * @param TotpValidatorInterface|null $totpValidator The TOTP validator to use.
     * @param HotpValidatorInterface|null $hotpValidator The HOTP validator to use.
     */
    public function __construct(
        TotpValidatorInterface $totpValidator = null,
        HotpValidatorInterface $hotpValidator = null
    ) {
        if (null === $totpValidator) {
            $totpValidator = new TotpValidator;
        }
        if (null === $hotpValidator) {
            $hotpValidator = new HotpValidator;
        }

        $this->totpValidator = $totpValidator;
        $this->hotpValidator = $hotpValidator;
    }

    /**
     * Get the TOTP validator.
     *
     * @return TotpValidatorInterface The TOTP validator.
     */
    public function totpValidator()
    {
        return $this->totpValidator;
    }

    /**
     * Get the HOTP validator.
     *
     * @return HotpValidatorInterface The HOTP validator.
     */
    public function hotpValidator()
    {
        return $this->hotpValidator;
    }

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\MfaParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\MfaValidationResultInterface            The validation result.
     * @throws Exception\UnsupportedMfaConfigurationException If the configuration is not supported.
     * @throws Exception\MfaParametersTypeMismatchException   If the parameters are the wrong type for the validator.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        Parameters\MfaParametersInterface $parameters
    ) {
        if ($configuration instanceof TotpConfigurationInterface) {
            if (!$parameters instanceof TotpParametersInterface) {
                throw new Exception\MfaParametersTypeMismatchException(
                    __NAMESPACE__ . '\Parameters\TotpParametersInterface',
                    $parameters
                );
            }

            return $this->totpValidator()->validate(
                $configuration,
                $parameters
            );
        } elseif ($configuration instanceof HotpConfigurationInterface) {
            if (!$parameters instanceof HotpParametersInterface) {
                throw new Exception\MfaParametersTypeMismatchException(
                    __NAMESPACE__ . '\Parameters\HotpParametersInterface',
                    $parameters
                );
            }

            return $this->hotpValidator()->validate(
                $configuration,
                $parameters
            );
        }

        throw new Exception\UnsupportedMfaConfigurationException(
            $configuration
        );
    }

    private $totpValidator;
    private $hotpValidator;
}

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

use Eloquent\Otis\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Configuration\OtpConfigurationInterface;
use Eloquent\Otis\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Validator\HotpValidatorInterface;
use Eloquent\Otis\Validator\TotpValidator;
use Eloquent\Otis\Validator\TotpValidatorInterface;

/**
 * A generic OTP validator supporting both TOTP and HOTP validation.
 */
class OtpValidator implements OtpValidatorInterface
{
    /**
     * Construct a new generic OTP validator.
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
     * Validate an OTP password.
     *
     * @param OtpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\OtpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\OtpValidationResultInterface            The validation result.
     * @throws Exception\UnsupportedOtpConfigurationException If the OTP configuration is not supported.
     * @throws Exception\OtpParametersTypeMismatchException   If the OTP parameters are the wrong type for the validator.
     */
    public function validate(
        OtpConfigurationInterface $configuration,
        Parameters\OtpParametersInterface $parameters
    ) {
        if ($configuration instanceof TotpConfigurationInterface) {
            if (!$parameters instanceof Parameters\TotpParametersInterface) {
                throw new Exception\OtpParametersTypeMismatchException(
                    __NAMESPACE__ . '\Parameters\TotpParametersInterface',
                    $parameters
                );
            }

            return $this->totpValidator()->validate(
                $configuration,
                $parameters
            );
        } elseif ($configuration instanceof HotpConfigurationInterface) {
            if (!$parameters instanceof Parameters\HotpParametersInterface) {
                throw new Exception\OtpParametersTypeMismatchException(
                    __NAMESPACE__ . '\Parameters\HotpParametersInterface',
                    $parameters
                );
            }

            return $this->hotpValidator()->validate(
                $configuration,
                $parameters
            );
        }

        throw new Exception\UnsupportedOtpConfigurationException(
            $configuration
        );
    }

    private $totpValidator;
    private $hotpValidator;
}

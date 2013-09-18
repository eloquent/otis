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
use Eloquent\Otis\Hotp\Validator\HotpValidator;
use Eloquent\Otis\Motp\Validator\MotpValidator;
use Eloquent\Otis\Totp\Validator\TotpValidator;

/**
 * A generic multi-factor authentication validator.
 */
class MfaValidator implements MfaValidatorInterface
{
    /**
     * Construct a new multi-factor authentication validator.
     *
     * @param array<MfaValidatorInterface>|null $validators The validators to aggregate.
     */
    public function __construct(array $validators = null)
    {
        if (null === $validators) {
            $validators = array(
                new TotpValidator,
                new HotpValidator,
                new MotpValidator,
            );
        }

        $this->validators = $validators;
    }

    /**
     * Get the aggregated validators.
     *
     * @return array<MfaValidatorInterface> The aggregated validators.
     */
    public function validators()
    {
        return $this->validators;
    }

    /**
     * Returns true if this validator supports the supplied combination of
     * configuration and parameters.
     *
     * @param MfaConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\MfaParametersInterface $parameters    The parameters to validate.
     *
     * @return boolean True if this validator supports the supplied combination.
     */
    public function supports(
        MfaConfigurationInterface $configuration,
        Parameters\MfaParametersInterface $parameters
    ) {
        foreach ($this->validators() as $validator) {
            if ($validator->supports($configuration, $parameters)) {
                return true;
            }
        }

        return false;
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
        Parameters\MfaParametersInterface $parameters
    ) {
        foreach ($this->validators() as $validator) {
            if ($validator->supports($configuration, $parameters)) {
                return $validator->validate($configuration, $parameters);
            }
        }

        throw new Exception\UnsupportedMfaCombinationException(
            $configuration,
            $parameters
        );
    }

    private $validators;
}

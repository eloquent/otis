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

use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;

/**
 * The interface implemented by mOTP validators.
 */
interface MotpValidatorInterface
{
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
    );
}

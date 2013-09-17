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

use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;

/**
 * The interface implemented by HOTP validators.
 */
interface HotpValidatorInterface
{
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
    );

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
    );
}

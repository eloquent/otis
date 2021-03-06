<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp;

use Eloquent\Otis\Driver\AbstractMfaDriver;
use Eloquent\Otis\Parameters\Generator\MfaSharedParametersGeneratorInterface;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\TimeBasedOtpValidator;

/**
 * Multi-factor authentication driver for mOTP.
 */
class MotpDriver extends AbstractMfaDriver
{
    /**
     * Construct a new mOTP driver.
     *
     * @param MfaValidatorInterface                      $validator                 The validator to use.
     * @param MfaSharedParametersGeneratorInterface|null $sharedParametersGenerator The shared parameters generator to use.
     */
    public function __construct(
        MfaValidatorInterface $validator = null,
        MfaSharedParametersGeneratorInterface $sharedParametersGenerator = null
    ) {
        if (null === $validator) {
            $valueGenerator = new Value\MotpValueGenerator;
            $validator = new TimeBasedOtpValidator($valueGenerator);
        }
        if (null === $sharedParametersGenerator) {
            $sharedParametersGenerator =
                new Parameters\Generator\MotpSharedParametersGenerator;
        }

        parent::__construct($validator, $sharedParametersGenerator);
    }
}

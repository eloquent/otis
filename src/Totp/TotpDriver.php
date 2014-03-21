<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp;

use Eloquent\Otis\Driver\AbstractMfaDriver;
use Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\GoogleAuthenticatorTotpUriFactory;
use Eloquent\Otis\Parameters\Generator\MfaSharedParametersGeneratorInterface;
use Eloquent\Otis\Parameters\Generator\TimeBasedOtpSharedParametersGenerator;
use Eloquent\Otis\Uri\Initialization\InitializationUriFactoryInterface;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\TimeBasedOtpValidator;

/**
 * Multi-factor authentication driver for TOTP.
 */
class TotpDriver extends AbstractMfaDriver
{
    /**
     * Construct a new TOTP driver.
     *
     * @param MfaValidatorInterface                      $validator                 The validator to use.
     * @param MfaSharedParametersGeneratorInterface|null $sharedParametersGenerator The shared parameters generator to use.
     * @param InitializationUriFactoryInterface|null     $initializationUriFactory  The initialization URI factory to use, or null if not supported.
     */
    public function __construct(
        MfaValidatorInterface $validator = null,
        MfaSharedParametersGeneratorInterface $sharedParametersGenerator = null,
        InitializationUriFactoryInterface $initializationUriFactory = null
    ) {
        if (null === $validator) {
            $valueGenerator = new Value\TotpValueGenerator;
            $validator = new TimeBasedOtpValidator($valueGenerator);
        }
        if (null === $sharedParametersGenerator) {
            $sharedParametersGenerator =
                new TimeBasedOtpSharedParametersGenerator;
        }
        if (null === $initializationUriFactory) {
            $initializationUriFactory = new GoogleAuthenticatorTotpUriFactory;
        }

        parent::__construct(
            $validator,
            $sharedParametersGenerator,
            $initializationUriFactory
        );
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Parameters\Generator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use Eloquent\Otis\Motp\Parameters\MotpSharedParametersInterface;
use Eloquent\Otis\Parameters\Generator\MfaSharedParametersGeneratorInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Icecave\Isolator\Isolator;

/**
 * Generates a set of mOTP shared parameters.
 */
class MotpSharedParametersGenerator implements
    MfaSharedParametersGeneratorInterface,
    MotpSharedParametersGeneratorInterface
{
    /**
     * Construct a new mOTP shared parameters generator.
     *
     * @param Isolator|null $isolator The isolator to use.
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Generate a set of multi-factor authentication shared parameters.
     *
     * @param MfaConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return MfaSharedParametersInterface The generated shared parameters.
     */
    public function generate(MfaConfigurationInterface $configuration)
    {
        return $this->generateMotp($configuration);
    }

    /**
     * Generate a set of mOTP shared parameters.
     *
     * @param MotpConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return MotpSharedParametersInterface The generated shared parameters.
     */
    public function generateMotp(MotpConfigurationInterface $configuration)
    {
        return new MotpSharedParameters(
            $this->isolator()->mcrypt_create_iv($configuration->secretLength()),
            str_pad($this->isolator()->mt_rand(0, 9999), 4, '0', STR_PAD_LEFT),
            $this->isolator()->time(),
            $this->isolator()
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

    private $isolator;
}

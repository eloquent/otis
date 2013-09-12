<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

/**
 * Represents a complete set of TOTP configuration settings.
 */
class TotpConfiguration extends AbstractOtpConfiguration implements
    TotpConfigurationInterface
{
    /**
     * Construct a new TOTP configuration.
     *
     * @param integer|null       $digits       The number of password digits.
     * @param integer|null       $window       The number of seconds each token is valid for.
     * @param integer|null       $secretLength The length of the shared secret.
     * @param HashAlgorithm|null $algorithm    The underlying algorithm to use.
     */
    public function __construct(
        $digits = null,
        $window = null,
        $secretLength = null,
        HashAlgorithm $algorithm = null
    ) {
        if (null === $window) {
            $window = 30;
        }

        parent::__construct($digits, $secretLength, $algorithm);

        $this->window = $window;
    }

    /**
     * Get the number of seconds each token is valid for.
     *
     * @return integer The number of seconds each token is valid for.
     */
    public function window()
    {
        return $this->window;
    }

    private $window;
}

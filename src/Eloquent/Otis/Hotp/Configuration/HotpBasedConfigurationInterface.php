<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Configuration;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;

/**
 * The interface implemented by one-time password authentication configurations
 * based upon HOTP.
 */
interface HotpBasedConfigurationInterface extends MfaConfigurationInterface
{
    /**
     * Get the number of password digits.
     *
     * @return integer The number of digits.
     */
    public function digits();

    /**
     * Get the length of the shared secret.
     *
     * @return integer The secret length.
     */
    public function secretLength();

    /**
     * Get the underlying algorithm name.
     *
     * @return HotpHashAlgorithm The algorithm name.
     */
    public function algorithm();
}

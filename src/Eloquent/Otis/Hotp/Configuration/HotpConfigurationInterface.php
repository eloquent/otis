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

/**
 * The interface implemented by HOTP configurations.
 */
interface HotpConfigurationInterface extends HotpBasedConfigurationInterface
{
    /**
     * Get the amount of counter increments to search through for a match.
     *
     * @return integer The amount of counter increments to search through for a match.
     */
    public function window();

    /**
     * Get the initial counter value.
     *
     * @return integer The initial counter value.
     */
    public function initialCounter();
}

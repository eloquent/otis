<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Parameters;

use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;

/**
 * The interface implemented by HOTP shared parameters.
 */
interface HotpSharedParametersInterface extends
    CounterBasedOtpSharedParametersInterface
{
}

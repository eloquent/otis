<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\QrCode;

use Eloquent\Enumeration\Enumeration;

/**
 * The available error correction levels for QR codes.
 */
final class ErrorCorrectionLevel extends Enumeration
{
    /**
     * Allows recovery of up to 7% data loss.
     */
    const LOW = 'L';

    /**
     * Allows recovery of up to 15% data loss.
     */
    const MEDIUM = 'M';

    /**
     * Allows recovery of up to 25% data loss.
     */
    const QUARTILE = 'Q';

    /**
     * Allows recovery of up to 30% data loss.
     */
    const HIGH = 'H';
}

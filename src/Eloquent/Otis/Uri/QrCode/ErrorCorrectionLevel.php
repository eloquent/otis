<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\QrCode;

use Eloquent\Enumeration\Multiton;

/**
 * The available error correction levels for QR codes.
 */
final class ErrorCorrectionLevel extends Multiton
{
    /**
     * Get the letter code.
     *
     * @return string The letter code.
     */
    public function letterCode()
    {
        return $this->letterCode;
    }

    /**
     * Get the number code.
     *
     * @return integer The number code.
     */
    public function numberCode()
    {
        return $this->numberCode;
    }

    protected static function initializeMembers()
    {
        // Allows recovery of up to 7% data loss.
        new static('LOW', 'L', 1);
        // Allows recovery of up to 15% data loss.
        new static('MEDIUM', 'M', 2);
        // Allows recovery of up to 25% data loss.
        new static('QUARTILE', 'Q', 3);
        // Allows recovery of up to 30% data loss.
        new static('HIGH', 'H', 4);
    }

    /**
     * @param string  $key
     * @param string  $letterCode
     * @param integer $numberCode
     */
    protected function __construct($key, $letterCode, $numberCode)
    {
        parent::__construct($key);

        $this->letterCode = $letterCode;
        $this->numberCode = $numberCode;
    }

    private $letterCode;
    private $numberCode;
}

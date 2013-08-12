<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp;

/**
 * Represents a generated HOTP value.
 */
class HotpValue
{
    /**
     * Construct a new HOTP value.
     *
     * @param string $value The raw value.
     */
    public function __construct($value)
    {
        $length = strlen($value);
        if (20 !== $length) {
            throw new Exception\InvalidResultLengthException($length);
        }

        $this->value = $value;
        $this->truncated = $this->truncate($value);
    }

    /**
     * Get the raw value.
     *
     * @return string The raw value.
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Get the truncated value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @return integer The truncated value.
     */
    public function truncated()
    {
        return $this->truncated;
    }

    /**
     * Generate a numeric string with a fixed number of digits from the result.
     *
     * @param integer|null $length The number of digits in the result string.
     *
     * @return string The result string.
     */
    public function string($length = null)
    {
        if (null === $length) {
            $length = 6;
        }
        if ($length < 6 || $length > 10) {
            throw new Exception\InvalidOutputLengthException($length);
        }

        return substr(
            str_pad(
                strval($this->truncated()),
                $length,
                '0',
                STR_PAD_LEFT
            ),
            -1 * $length
        );
    }

    /**
     * @param string $value
     *
     * @return integer
     */
    protected function truncate($value)
    {
        $hex = bin2hex($value);
        $parts = str_split($hex, 2);

        foreach ($parts as $i => $part) {
            $parts[$i] = hexdec($part);
        }

        $offset = $parts[19] & 0xf;

        return
            (($parts[$offset] & 0x7f) << 24) |
            (($parts[$offset + 1] & 0xff) << 16) |
            (($parts[$offset + 2] & 0xff) << 8) |
            ($parts[$offset + 3] & 0xff)
        ;
    }

    private $value;
    private $truncated;
}

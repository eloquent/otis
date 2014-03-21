<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Value;

use Eloquent\Otis\Exception\InvalidPasswordLengthException;

/**
 * Represents a generated HOTP value.
 */
class HotpValue implements HotpValueInterface
{
    /**
     * Construct a new HOTP value.
     *
     * @param string $value The raw value.
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->truncated = $this->truncate($value);
    }

    /**
     * Get the raw value.
     *
     * @return mixed The raw value.
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
     * Generate a string with a fixed number of characters from the result.
     *
     * @param integer|null $length The number of characters in the result string.
     *
     * @return string                         The result string.
     * @throws InvalidPasswordLengthException If the requested length is invalid.
     */
    public function string($digits = null)
    {
        if (null === $digits) {
            $digits = 6;
        }
        if ($digits < 6 || $digits > 10) {
            throw new InvalidPasswordLengthException($digits);
        }

        return substr(
            str_pad(
                strval($this->truncated()),
                $digits,
                '0',
                STR_PAD_LEFT
            ),
            -1 * $digits
        );
    }

    /**
     * Generate a string with the default number of characters from the result.
     *
     * @return string The result string.
     */
    public function __toString()
    {
        return $this->string(6);
    }

    /**
     * Truncate the supplied value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @param string $value The value to truncate.
     *
     * @return integer The truncated value.
     */
    protected function truncate($value)
    {
        $value = bin2hex($value);
        $parts = str_split($value, 2);

        foreach ($parts as $i => $part) {
            $parts[$i] = hexdec($part);
        }

        $offset = $parts[count($parts) - 1] & 0xf;

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

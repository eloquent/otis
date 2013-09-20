<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Exception;

use Exception;

/**
 * An unsupported combination of multi-factor authentication configuration,
 * shared parameters, and credentials was supplied.
 */
class UnsupportedMfaCombinationException extends Exception
{
    /**
     * Construct a new unsupported multi-factor authentication combination
     * exception.
     *
     * @param Exception|null $previous The cause, if available.
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct(
            'Unsupported combination of multi-factor authentication, ' .
                'configuration, shared parameters, and credentials.',
            0,
            $previous
        );
    }
}

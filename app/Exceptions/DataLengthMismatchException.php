<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class DataLengthMismatchException extends RuntimeException
{
    public function __construct(int $x, int $y)
    {
        parent::__construct("Data length mismatch: {$x} != {$y}");
    }
}

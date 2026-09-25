<?php

namespace App\Exceptions;

use RuntimeException;

class OrdenProduccionException extends RuntimeException
{
    /**
     * @param  list<array<string, mixed>>  $faltantes
     */
    public function __construct(
        string $message,
        public readonly array $faltantes = [],
    ) {
        parent::__construct($message);
    }
}

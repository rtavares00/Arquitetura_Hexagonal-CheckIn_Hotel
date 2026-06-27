<?php

namespace Tavares\Hotel\Adapter\Exception;

use RuntimeException;

class ReservaNaoEncontradaException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Reserva não encontrada para o id: {$id}");
    }
}

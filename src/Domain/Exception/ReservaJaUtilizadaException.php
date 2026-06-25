<?php

namespace Tavares\Hotel\Domain\Exception;

use DomainException;

class ReservaJaUtilizadaException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("A reserva {$id} já foi utilizada e não pode ser utilizada novamente.");
    }
}

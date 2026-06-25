<?php

namespace Tavares\Hotel\Domain\Exception;

use DomainException;

class CheckinAntesDaEntradaException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("O check-in da reserva {$id} não pode ser feito antes da data de entrada.");
    }
}

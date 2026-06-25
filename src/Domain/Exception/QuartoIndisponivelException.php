<?php

namespace Tavares\Hotel\Domain\Exception;

use DomainException;

class QuartoIndisponivelException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("O quarto {$id} não está disponível para reserva.");
    }
}

<?php

namespace Tavares\Hotel\Domain\Exception;

use DomainException;

class DatasInvalidasException extends DomainException
{
    public function __construct()
    {
        parent::__construct('As datas da reserva são inválidas: a saída não pode ser anterior à entrada.');
    }
}

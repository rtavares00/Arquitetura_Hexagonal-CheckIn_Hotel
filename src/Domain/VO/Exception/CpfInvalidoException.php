<?php

namespace Tavares\Hotel\Domain\VO\Exception;

use InvalidArgumentException;

class CpfInvalidoException extends InvalidArgumentException
{
    public function __construct(string $cpf)
    {
        parent::__construct("O CPF informado é inválido: {$cpf}");
    }
}

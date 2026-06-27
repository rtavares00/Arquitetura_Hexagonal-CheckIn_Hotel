<?php

namespace Tavares\Hotel\Adapter\Exception;

use RuntimeException;

class QuartoNaoEncontradoException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Quarto não encontrado para o id: {$id}");
    }
}

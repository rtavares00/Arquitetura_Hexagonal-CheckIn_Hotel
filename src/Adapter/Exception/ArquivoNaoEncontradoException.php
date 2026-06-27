<?php

namespace Tavares\Hotel\Adapter\Exception;

use RuntimeException;

class ArquivoNaoEncontradoException extends RuntimeException
{
    public function __construct(string $caminho)
    {
        parent::__construct("O arquivo de persistência não foi encontrado: {$caminho}");
    }
}

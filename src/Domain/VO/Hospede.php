<?php

namespace Tavares\Hotel\Domain\VO;

use Tavares\Hotel\Domain\VO\Exception\CpfInvalidoException;

class Hospede{

    public function __construct(private string $cpf){

        if (!$this->cpfEhValido($cpf)) {
            throw new CpfInvalidoException($cpf);
        }
    }

    private function cpfEhValido(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($posicao = 9; $posicao < 11; $posicao++) {
            $soma = 0;

            for ($i = 0; $i < $posicao; $i++) {
                $soma += (int) $cpf[$i] * (($posicao + 1) - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$posicao] !== $digito) {
                return false;
            }
        }

        return true;
    }

}

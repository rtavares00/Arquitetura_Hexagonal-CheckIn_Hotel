<?php

namespace Tavares\Hotel\Domain;
use DateTime;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Domain\Exception\DatasInvalidasException;

//Uma reserva é feita para um hóspede, em um quarto, com data de entrada e data de saída.
class Reserva{

    public function __construct(
        private Hospede $hospede,
        private Quarto $quarto,
        private DateTime $entrada,
        private DateTime $saida
    )
    {
        if($this->saida >= $this->entrada == false):
            throw new DatasInvalidasException();
        endif;
    }

    public function acionar():void
    {
        $this->quarto->reservar();
    }

}
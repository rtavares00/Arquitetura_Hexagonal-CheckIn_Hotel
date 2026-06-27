<?php

namespace Tavares\Hotel\Domain;
use DateTime;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Domain\Exception\DatasInvalidasException;
use Tavares\Hotel\Domain\Exception\ReservaJaUtilizadaException;
use Tavares\Hotel\Domain\Exception\CheckinAntesDaEntradaException;

//Uma reserva é feita para um hóspede, em um quarto, com data de entrada e data de saída.
class Reserva{

    public function __construct(
        private int $id,
        private Hospede $hospede,
        private Quarto $quarto,
        private DateTime $entrada,
        private DateTime $saida,
        private bool $utilizada
    )
    {
        if($this->saida < $this->entrada):
            throw new DatasInvalidasException();
        endif;
    }

    public function id():int
    {
        return $this->id;
    }

    public function quarto():Quarto
    {
        return $this->quarto;
    }

    public function hospede(): Hospede
    {
        return $this->hospede;
    }

    public function isUtilizada():bool
    {
        return $this->utilizada;
    }

    public function entrada():DateTime
    {
        return $this->entrada;
    }

    public function saida():DateTime
    {
        return $this->saida;
    }

    public function acionar(DateTime $dataCheckin):string
    {
        if($dataCheckin < $this->entrada):
            throw new CheckinAntesDaEntradaException($this->id);
        endif;

        if($this->utilizada):
            throw new ReservaJaUtilizadaException($this->id);
        endif;

        $this->quarto->ocupar();
        $this->utilizada = true;

        return "Check-in confirmado. Quarto: {$this->quarto->id()}";
    }

}

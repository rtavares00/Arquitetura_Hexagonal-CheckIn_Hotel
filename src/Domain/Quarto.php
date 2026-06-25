<?php

namespace Tavares\Hotel\Domain;
use Tavares\Hotel\Domain\Enum\Status;

Class Quarto{
    
    public function __construct(private int $id , private Status $status)
    {

    }

    public function isOcupado():bool
    {
        return $this->status == Status::Ocupado;
    }

    public function isEmManutencao():bool
    {
        return $this->status == Status::Manutencao;
    }

    public function reservar():void
    {
        $this->status = Status::Ocupado;
    }

}
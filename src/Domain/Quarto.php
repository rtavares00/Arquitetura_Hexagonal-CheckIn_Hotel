<?php

namespace Tavares\Hotel\Domain;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Domain\Exception\QuartoIndisponivelException;

Class Quarto{
    
    public function __construct(private int $id , private Status $status)
    {

    }

    public function id():int
    {
        return $this->id;
    }

    public function isOcupado():bool
    {
        return $this->status == Status::Ocupado;
    }

    public function isEmManutencao():bool
    {
        return $this->status == Status::Manutencao;
    }

    public function ocupar():void
    {
        if($this->status != Status::Disponivel){
            throw new QuartoIndisponivelException($this->id);
        }

        $this->status = Status::Ocupado;
    }

}
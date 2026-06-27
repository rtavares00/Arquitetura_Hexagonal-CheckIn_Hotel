<?php
namespace Tavares\Hotel\Port;
use Tavares\Hotel\Domain\Reserva;

interface ReservaRepository{
    
    public function buscar(int $id):Reserva;
    public function salvar(Reserva $reserva):void;
}
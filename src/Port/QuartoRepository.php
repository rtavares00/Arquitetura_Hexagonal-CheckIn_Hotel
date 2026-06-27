<?php
namespace Tavares\Hotel\Port;
use Tavares\Hotel\Domain\Quarto;

interface QuartoRepository{
    
    public function buscar(int $id):Quarto;
    public function salvar(Quarto $quarto):void;
}
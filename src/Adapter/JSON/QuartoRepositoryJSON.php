<?php

namespace Tavares\Hotel\Adapter\JSON;

use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Port\QuartoRepository;
use Tavares\Hotel\Adapter\Exception\ArquivoNaoEncontradoException;
use Tavares\Hotel\Adapter\Exception\QuartoNaoEncontradoException;

class QuartoRepositoryJSON implements QuartoRepository
{
    private string $filepath;
    private array $quartos;

    public function __construct(){
        $this->filepath = __DIR__ . "/../../../data/quartos.json";

        if(!file_exists($this->filepath)):
            throw new ArquivoNaoEncontradoException($this->filepath);
        endif;

        $this->quartos = json_decode( file_get_contents($this->filepath) ,true);
    }

    public function buscar(int $id):Quarto
    {
        foreach($this->quartos as $quarto):
            if($quarto['id'] == $id):
                return new Quarto($quarto['id'],Status::from($quarto['status']));
            endif;
        endforeach;

        throw new QuartoNaoEncontradoException($id);
    }
    
    public function salvar(Quarto $quarto):void
    {
        $localizou = false;
        for($c = 0; $c < count($this->quartos) && $localizou == false ; $c++)
        {
            if($this->quartos[$c]['id'] == $quarto->id()):
                $this->quartos[$c] = [
                    'id'     => $quarto->id(),
                    'status' => $quarto->status()->value,
                ];
                $localizou = true;
            endif;
        }

        if($localizou == false):
            throw new QuartoNaoEncontradoException($quarto->id());
        endif;

        file_put_contents( $this->filepath, json_encode($this->quartos,JSON_PRETTY_PRINT) );
    }
}
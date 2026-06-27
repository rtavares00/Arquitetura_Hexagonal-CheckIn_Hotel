<?php

namespace Tavares\Hotel\Adapter\JSON;

use DateTime;
use Tavares\Hotel\Domain\Reserva;
use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Port\ReservaRepository;
use Tavares\Hotel\Port\QuartoRepository;
use Tavares\Hotel\Adapter\Exception\ArquivoNaoEncontradoException;
use Tavares\Hotel\Adapter\Exception\ReservaNaoEncontradaException;

class ReservaRepositoryJSON implements ReservaRepository
{
    private string $filepath;
    private array $reservas;

    public function __construct(private QuartoRepository $quartoRepository){
        $this->filepath = __DIR__ . "/../../../data/reservas.json";

        if(!file_exists($this->filepath)):
            throw new ArquivoNaoEncontradoException($this->filepath);
        endif;

        $this->reservas = json_decode( file_get_contents($this->filepath) ,true);
    }

    public function buscar(int $id):Reserva
    {
        
        foreach($this->reservas as $reserva):
            if($reserva['id'] == $id):
               
                return new Reserva(
                   $reserva['id'],
                   new Hospede($reserva['cpfHospede']),
                   $this->quartoRepository->buscar($reserva['idQuarto']),
                   new DateTime($reserva['entrada']),
                   new DateTime($reserva['saida']),
                   $reserva['utilizada']
                );

            endif;
        endforeach;

        throw new ReservaNaoEncontradaException($id);
    }
    
    public function salvar(Reserva $reserva):void
    {
        $localizou = false;
        for($c = 0; $c < count($this->reservas) && $localizou == false ; $c++)
        {
            if($this->reservas[$c]['id'] == $reserva->id()):
                $this->reservas[$c] = [
                    'id'     => $reserva->id(),
                    'cpfHospede' => $reserva->hospede()->cpf(),
                    'idQuarto' => $reserva->quarto()->id(),
                    'entrada' => $reserva->entrada()->format('Y-m-d'),
                    'saida' => $reserva->saida()->format('Y-m-d'),
                    'utilizada' => $reserva->isUtilizada()
                ];
                $localizou = true;
            endif;
        }

        if($localizou == false):
            throw new ReservaNaoEncontradaException($reserva->id());
        endif;

        file_put_contents( $this->filepath, json_encode($this->reservas,JSON_PRETTY_PRINT) );
    }
}
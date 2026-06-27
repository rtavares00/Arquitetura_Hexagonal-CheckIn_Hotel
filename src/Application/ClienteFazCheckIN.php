<?php
namespace Tavares\Hotel\Application;

use DateTime;
//use Tavares\Hotel\Domain\Quarto;
//use Tavares\Hotel\Domain\Reserva;
//use Tavares\Hotel\Domain\VO\Hospede;

use Tavares\Hotel\Port\ReservaRepository;
use Tavares\Hotel\Port\QuartoRepository;
use Tavares\Hotel\Port\Notificador;

class ClienteFazCheckIN
{
    public function __construct(
        private ReservaRepository $reservas,
        private QuartoRepository $quartos,
        private Notificador $notificador
    )
    {

    }

    public function acionar(int $idReserva,DateTime $dataCheckin):void
    {
        $reserva = $this->reservas->buscar($idReserva);
        $reserva->acionar($dataCheckin);
        $this->reservas->salvar($reserva);
        $this->quartos->salvar($reserva->quarto());

        $this->notificador->confirmarCheckin($reserva->quarto()->id());
    }
}
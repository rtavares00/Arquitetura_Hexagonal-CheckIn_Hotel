<?php

use Tavares\Hotel\Application\ClienteFazCheckIN;
use Tavares\Hotel\Domain\Reserva;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Port\ReservaRepository;
use Tavares\Hotel\Port\QuartoRepository;
use Tavares\Hotel\Port\NotificacaoRepository;
use Tavares\Hotel\Domain\Exception\CheckinAntesDaEntradaException;

/*
|--------------------------------------------------------------------------
| Fakes (adaptadores em memória das portas)
|--------------------------------------------------------------------------
*/

function reservaRepositoryFake(Reserva $reserva): ReservaRepository
{
    return new class($reserva) implements ReservaRepository {
        public array $salvas = [];
        public function __construct(private Reserva $reserva) {}
        public function buscar(int $id): Reserva { return $this->reserva; }
        public function salvar(Reserva $reserva): void { $this->salvas[] = $reserva; }
    };
}

function quartoRepositoryFake(): QuartoRepository
{
    return new class implements QuartoRepository {
        public array $salvos = [];
        public function buscar(int $id): Quarto { throw new RuntimeException('não utilizado neste fluxo'); }
        public function salvar(Quarto $quarto): void { $this->salvos[] = $quarto; }
    };
}

function notificadorFake(): NotificacaoRepository
{
    return new class implements NotificacaoRepository {
        public ?string $mensagem = null;
        public function notificarCliente(string $mensagem): void { $this->mensagem = $mensagem; }
    };
}

function reservaParaCheckin(bool $utilizada = false, Status $statusQuarto = Status::Disponivel): Reserva
{
    return new Reserva(
        1,
        new Hospede('529.982.247-25'),
        new Quarto(101, $statusQuarto),
        new DateTime('2026-06-25'),
        new DateTime('2026-06-28'),
        $utilizada,
    );
}

/*
|--------------------------------------------------------------------------
| acionar (caso de uso de check-in)
|--------------------------------------------------------------------------
*/

test('check-in com sucesso: ocupa o quarto, marca a reserva, persiste e notifica o cliente', function () {
    $reserva = reservaParaCheckin();
    $reservas = reservaRepositoryFake($reserva);
    $quartos = quartoRepositoryFake();
    $notificador = notificadorFake();

    $useCase = new ClienteFazCheckIN($reservas, $quartos, $notificador);
    $useCase->acionar(1, new DateTime('2026-06-25'));

    expect($reserva->quarto()->isOcupado())->toBeTrue()
        ->and($reserva->isUtilizada())->toBeTrue()
        ->and($reservas->salvas)->toHaveCount(1)
        ->and($quartos->salvos)->toHaveCount(1)
        ->and($notificador->mensagem)->toContain('101');
});

test('check-in inválido propaga a exceção do domínio e não notifica o cliente', function () {
    $reserva = reservaParaCheckin();
    $notificador = notificadorFake();

    $useCase = new ClienteFazCheckIN(
        reservaRepositoryFake($reserva),
        quartoRepositoryFake(),
        $notificador,
    );

    expect(fn () => $useCase->acionar(1, new DateTime('2026-06-24')))
        ->toThrow(CheckinAntesDaEntradaException::class);

    expect($notificador->mensagem)->toBeNull();
});

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tavares\Hotel\Adapter\JSON\QuartoRepositoryJSON;
use Tavares\Hotel\Adapter\JSON\ReservaRepositoryJSON;
use Tavares\Hotel\Adapter\NotificacaoEmTela;
use Tavares\Hotel\Application\ClienteFazCheckIN;

function realizarCheckin(string $idArg, string $dataArg): void
{
    $idReserva   = (int) $idArg;
    $dataCheckin = new DateTime($dataArg);

    $quartoRepository  = new QuartoRepositoryJSON();
    $reservaRepository = new ReservaRepositoryJSON($quartoRepository);

    $useCase = new ClienteFazCheckIN($reservaRepository, $quartoRepository, new NotificacaoEmTela());
    $useCase->acionar($idReserva, $dataCheckin);
}

if ($argc < 3) {
    echo "Uso: php src/index.php <idReserva> <dataCheckin no formato Y-m-d>\n";
    exit(1);
}

try {
    realizarCheckin($argv[1], $argv[2]);
} catch (Throwable $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}

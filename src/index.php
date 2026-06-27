<?php

require_once __DIR__ . '/../vendor/autoload.php';

//use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Adapter\JSON\QuartoRepositoryJSON;
use Tavares\Hotel\Adapter\JSON\ReservaRepositoryJSON;
use Tavares\Hotel\Adapter\NotificacaoEmTela;

use Tavares\Hotel\Application\ClienteFazCheckIN;

if ($argc < 3):
    echo "Uso: php src/index.php <idReserva> <dataCheckin no formato Y-m-d>\n";
    exit(1);
endif;

$idReserva   = (int) $argv[1];
$dataCheckin = new DateTime($argv[2]);

$quartoRepository = new QuartoRepositoryJSON();
$reservaRepository = new ReservaRepositoryJSON($quartoRepository);

$useCase = new ClienteFazCheckIN($reservaRepository,$quartoRepository,new NotificacaoEmTela());
$useCase->acionar($idReserva, $dataCheckin);

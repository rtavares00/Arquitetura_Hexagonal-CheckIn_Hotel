<?php
//COMPOSITION ROOT - IGNORAR POR ENQUANTO
/*
Um hotel gerencia reservas e quartos. Quando o hóspede chega, é feito o check-in.
Regras de negócio

Um quarto tem um status: Disponível, Ocupado ou EmManutenção.
Uma reserva é feita para um hóspede, em um quarto, com data de entrada e data de saída.
O check-in só pode ser feito a partir da data de entrada da reserva (não pode antes).
O check-in não pode ser feito se o quarto não estiver Disponível.
O check-in não pode ser feito se a reserva já tiver sido utilizada (não pode fazer check-in duas vezes na mesma reserva).
Ao fazer o check-in com sucesso: o quarto passa a Ocupado, a reserva é marcada como utilizada, e o hóspede recebe uma confirmação com o número do quarto.
*/
require_once __DIR__ . '/../vendor/autoload.php';
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Reserva;
use Tavares\Hotel\Domain\VO\Hospede;

$hospede = new Hospede("142.815.957-67");

//$reserva = new Reserva($id,$hospede,$quarto,$entrada,$utilizada);
//$reserva->acionar();
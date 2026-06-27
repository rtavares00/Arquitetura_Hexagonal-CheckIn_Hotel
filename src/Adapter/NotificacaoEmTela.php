<?php
namespace Tavares\Hotel\Adapter;

use Tavares\Hotel\Port\NotificacaoRepository;


class NotificacaoEmTela implements NotificacaoRepository{
    
    public function notificarCliente(string $mensagem):void
    {
        echo "Notificação em Tela: \n\n";
        echo $mensagem;
        echo "\n\n";
    }
}
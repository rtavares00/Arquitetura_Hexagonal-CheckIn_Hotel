<?php
namespace Tavares\Hotel\Port;


interface NotificacaoRepository{
    
    public function notificarCliente(string $mensagem):void;
}
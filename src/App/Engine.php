<?php

namespace Komodo\Exodus\App;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: Engine.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

use Error;
use Komodo\Exodus\Session;
use Komodo\Logger\Logger;
use Komodo\Routes\Router;

class Engine
{
    use EngineStatic;
    protected $session;
    protected $router;
    protected $logger;

    public function __construct()
    {
    }

    public function start()
    {

        if (!$this->session || !$this->router) {
            throw new Error("Missing services", 1);
        }

        $this->session->export();
        $this->router->listen(true, null, $this->logger);

    }

    function use ($resource) {
        switch (true) {
            case $resource instanceof Router:
                $this->router = $resource;
                break;
            case $resource instanceof Session:
                $this->session = $resource;
                break;
            case $resource instanceof Logger:
                $this->logger = $resource;
                break;
            default:
                include_once $resource;
                break;
        }
    }
}

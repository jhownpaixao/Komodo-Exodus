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

use Komodo\Exodus\Session;
use Komodo\Logger\Logger;
use Komodo\Routes\Router;

trait EngineStatic
{

    /**
     * @var Engine
     */
    public static $current;

    /**
     * Get engine session
     *
     * @return Session
     */
    public static function session()
    {
        return self::$current->session;
    }

    /**
     * Get engine router
     *
     * @return Router
     */
    public static function router()
    {
        return self::$current->router;
    }

    /**
     * Get engine logger
     *
     * @return Logger
     */
    public static function logger()
    {
        return self::$current->logger;
    }

    /**
     * Get current engine instance
     *
     * @return Engine
     */
    public static function get()
    {
        return self::$current;
    }

    /**
     * Create a new engine instance
     *
     * @param array $resources
     *
     * @return Engine
     */
    public static function create($resources = [  ])
    {
        self::$current = new Engine();
        self::$current->use(new \Komodo\Exodus\Routing\Router );

        foreach ($resources as $resource) {
            self::$current->use($resource);
        }

        return self::$current;
    }

}

<?php

namespace Komodo\Exodus\App;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: Middleware.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

class Middleware
{
    /**
     * Get complete path to class -method middleware
     *
     * @param string $method
     *
     * @return string
     */
    public static function method($method)
    {
        return self::class . "::$method";
    }
}

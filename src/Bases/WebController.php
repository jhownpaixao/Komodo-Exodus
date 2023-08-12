<?php

namespace Komodo\Exodus\Bases;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: WebController.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

use Komodo\Exodus\Interfaces\IController;
use Komodo\Routes\Enums\HTTPResponseCode;
use Komodo\Routes\Error\ResponseError;

abstract class WebController implements IController
{
    public static $data;
    public static $view;
    public static $template;

    /**
     * @param string $template
     * @param string $view
     * @param array $data
     *
     * @return void
     */
    public static function view($template, $view, $data = [  ])
    {

        self::$template = $template;
        self::$view = $view;
        self::$data = $data;

        if (file_exists($template)) {
            extract(self::$data);
            require_once $template;
        } else {
            throw new ResponseError('Não foi possível encontrar o template', HTTPResponseCode::informationNotFound);
        }
    }

    public static function outlet()
    {
        if (file_exists(self::$view)) {
            require self::$view;
        } else {
            throw new ResponseError('Não foi possível encontrar a view', HTTPResponseCode::informationNotFound);
        }
    }
}

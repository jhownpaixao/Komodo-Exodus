<?php

namespace Komodo\Exodus\App;

use Komodo\Routes\Request;
use Komodo\Routes\Response;
use Komodo\Routes\Router;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: Controller.php
| Data da Criação Sat Aug 12 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/


abstract class Controller
{

    /**
     * Request object
     *
     * @var Request
     */
    public $request;

    /**
     * Response object
     *
     * @var Response
     */
    public $response;


    /**
     * create
     *
     * @param Request $request
     * @param Response $response
     * 
     * @return $this
     */
    public static function create($request, $response)
    {
        $class = get_called_class();
        return new $class($request, $response);
    }

    /**
     * __construct
     *
     * @param Request $request
     * @param Response $response
     * 
     * @return $this
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

}

<?php

namespace Komodo\Exodus\Routing;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: Router.php
| Data da Criação Sat Aug 12 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

use Komodo\Exodus\App\Controller;
use Komodo\Logger\Logger;
use Komodo\Routes\CORS\CORSOptions;
use Komodo\Routes\Enums\HTTPMethods;
use Komodo\Routes\Enums\HTTPResponseCode;
use Komodo\Routes\Error\ResponseError;
use Komodo\Routes\Matcher;
use Komodo\Routes\Request;
use Komodo\Routes\Response;
use Komodo\Routes\Router as NativeRouter;

class Router extends NativeRouter
{

    /**
     * @param bool $handleErros
     * @param CORSOptions|null $cors
     * @param Logger|null $logger
     *
     * @return void
     */
    public static function listen($handleErros = false, $cors = null, $logger = null)
    {
        if ($handleErros) {
            set_error_handler(function ($errno, $errstr) {
                throw new ResponseError($errstr, $errno);
            });
        }
        self::$logger = $logger ? clone $logger : new Logger;
        self::$logger->register('Komodo\\Exodus\\Router');
        $matcher = new Matcher(self::$routes, self::$prefixes);
        $response = new Response($cors ?: new CORSOptions);

        $route = null;
        $matcher->match();

        self::$logger->debug([
            "route" => $matcher->path,
            "founded" => $matcher->route,
            "method" => $matcher->method->value,
         ], 'Inicializando rotas');

        #Verificar se a rota existe
        if (!$matcher->route) {
            self::$logger->debug('Rota não encontrada');
            $response->write([
                "message" => "Rota não encontrada",
                "status" => false,
             ])->status(HTTPResponseCode::informationNotFound)->sendJson();
        }

        #Se for um grupo de rotas
        if (is_array($matcher->route)) {
            foreach ($matcher->route as $var) {
                if ($var->method === $matcher->method) {
                    $route = $var;
                }
            }
        } elseif (self::validadeMethod($matcher->method, $matcher->route)) {
            $route = $matcher->route;
        }

        #Se for o method OPTIONS
        if (HTTPMethods::options == $matcher->method) {
            $options = self::filterOptions($matcher->route);
            $alloweds = self::generateAllowedMethods($options);
            $response->header('Allow', implode(', ', $alloweds))->send();
        }

        #Se o method é permitido
        if ($matcher->route && !$route) {
            self::$logger->debug('Método não implementado');
            $options = self::filterOptions($matcher->route);
            $alloweds = self::generateAllowedMethods($options);
            $response
                ->write([
                    "message" => "Método não implementado",
                    "status" => false,
                 ])
                ->status(HTTPResponseCode::methodNotAllowed)
                ->header('Access-Control-Allow-Methods', implode(', ', $alloweds))
                ->sendJson();
        }

        #Definindo rota requisitada
        self::$current = $route->path;

        $request = new Request($matcher->params, $_GET ?: [  ], apache_request_headers(), $matcher->method);

        #Executa as middlewares
        if ($route->middlewares) {
            self::processCallbacks($route->middlewares, $request, $response);
        }

        #Executar o controller
        /**
         * @var Controller
         */
        $controller = call_user_func_array([ $route->callback, 'create' ], [ $request, $response ]);

        $method = self::$current ?: 'index';
        $method = method_exists($controller, $method) ? $method : 'index';
        call_user_func([ $controller, $method ]);
    }
}

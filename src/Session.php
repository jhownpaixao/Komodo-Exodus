<?php

namespace Komodo\Exodus;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: Session.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

use Komodo\Logger\Logger;
use Komodo\Store\Store;

class Session
{
    private $maq;
    private $max_time;
    private $cookie_age;
    public array $data;
    private $auth;
    public $ssid;
    private $cookie_name;
    private Logger $logger;
    private Store $store;

    public function __construct(array $params = [  ], $logger = null)
    {
        $SessionOptions = [
            'MAX_TIME' => 3500,
            'cache' => [
                'dir' => './sessions',
                'file' => 'sessions.json',
             ],
         ];

        $config = array_merge($SessionOptions, $params);
        $this->max_time = $config[ 'MAX_TIME' ];
        $this->logger = $logger ? clone $logger : new Logger;
        $this->store = new Store($config[ 'cache' ][ 'dir' ], $config[ 'cache' ][ 'file' ], $logger);
        $this->cookie_age = 60 * 60 * 24;
        $this->cookie_name = 'PHPEXSSD';
        $this->init();
    }

    private function init()
    {
        $this->logger->register('Komodo\\Exodus\\Session');
        $this->logger->debug('Starting session');
        $this->load();
    }

    private function create()
    {
        $session = array(
            "ssid" => uniqid(),
            "created_at" => date('Y-m-d H:i:s'),
            "data" => [  ],
            "auth" => [  ],
        );
        $this->store->set($session[ 'ssid' ], $session);

        setcookie(
            $this->cookie_name,
            $session[ 'ssid' ],
            [
                'expires' => time() + $this->cookie_age,
                'path' => '/',
                //'domain' => _EX_SERV_DOMAIN,
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict',
             ]
        );
        return $session[ 'ssid' ];
    }
    private function destroy()
    {
        $this->store->delete($this->ssid);
        $this->ssid = null;
        $this->data = [  ];
        $this->auth = null;
        unset($_COOKIE[ $this->cookie_name ]);
        setcookie($this->cookie_name, null, -1, '/');
    }

    private function load()
    {
        $ssid = $this->sessionFromCookie();

        if (!$ssid || !$this->store->has($ssid)) {
            $ssid = $this->create();
        };
        $stored = $this->store->get($ssid);

        $this->ssid = $ssid;
        $this->data = $stored[ 'data' ];
        $this->auth = $stored[ 'auth' ];
    }

    public function export()
    {
        define('SESSION', $this->data);
    }
    private function sessionFromCookie()
    {
        $this->logger->debug('Loading data from cookie');
        if (isset($_COOKIE[ $this->cookie_name ])) {
            return $_COOKIE[ $this->cookie_name ];
        }

        return false;
    }

    public function auth()
    {
        $dif = date_diff(new \DateTime(trim($this->auth[ 'timestamp' ])), new \DateTime('now'));
        $intval = intval($dif->format("%S")) + (intval($dif->format("%I")) * 60) + (intval($dif->format("%H")) * 60);

        if ($intval < intval($this->auth[ 'expires' ])) {
            return true;
        }

        $this->destroy();

        return false;
    }

    public function getSSID()
    {
        return $this->ssid;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[ $key ] = $value;
        $this->save();
    }
    public function save()
    {
        $stored = $this->store->get($this->ssid);

        $stored[ 'data' ] = $this->data;
        $stored[ 'auth' ] = $this->auth;
        $this->store->set($this->ssid, $stored);
    }
}

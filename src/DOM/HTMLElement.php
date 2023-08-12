<?php

namespace Komodo\Exodus\DOM;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: ComponentsBase.php
| Data da Criação Sun Aug 06 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

class HTMLElement
{
    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function div($params, $cb = null)
    {
        echo self::component('div', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function img($params, $cb = null)
    {
        echo self::component('img', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function body($params, $cb = null)
    {
        echo self::component('body', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function a($params, $cb = null)
    {
        echo self::component('a', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function script($params, $cb = null)
    {
        echo self::component('script', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function head($params, $cb = null)
    {
        echo self::component('head', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function meta($params, $cb = null)
    {
        echo self::component('meta', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function title($params, $cb = null)
    {
        echo self::component('title', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function link($params, $cb = null)
    {
        echo self::component('link', $params, $cb);
    }

    /**
     * @param array<string,string> $params
     * @param \Closure|string $cb
     *
     * @return void
     */
    public static function footer($params, $cb = null)
    {
        echo self::component('footer', $params, $cb);
    }

    public static function innerHTML($content)
    {
        echo $content;
    }

    /**
     * @param array<string,string> $properties
     *
     * @return string
     */
    public static function getHTMLElementPoperties($properties)
    {
        array_walk($properties, function (&$var, $prop) {
            $var = "$prop='$var'";
        });
        return implode(' ', $properties);
    }

    /**
     * @param string $tag
     * @param array $properties
     * @param \Closure|string $cb
     *
     * @return mixed
     */
    private static function component($tag, $properties, $cb = null)
    {
        $properties = self::getHTMLElementPoperties($properties);
        $inner = "<$tag $properties>%s</$tag>";
        $ret = '';
        if ($cb) {
            switch (gettype($cb) == 'string') {
                case 'value':
                    $ret = $cb;
                    break;
                default:
                    ob_start();
                    $cb();
                    $ret = ob_get_clean();
                    break;
            }
        }
        return sprintf($inner, $ret);
    }
}

<?php

namespace Komodo\Exodus\DOM;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: DOM.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/
class DOM
{
    public static function generate(array $domElements)
    {
        $element = '';
        foreach ($domElements as $elementData) {
            $el = $elementData[ 'el' ] ?? null;
            $content = $elementData[ 'content' ] ?? '';
            $attrs = $elementData[ 'attrs' ] ?? [  ];

            switch (gettype($content)) {
                case 'string':
                    $data = $content;
                    break;

                case 'array':
                    $data = self::generate($content);
                    break;
            }

            $element .= self::createElement($el, $attrs, $data);
        }

        return $element;
    }

    public static function createElement(string | null $el, $attrs = [  ], $content = '')
    {
        if ($el) {
            $atributes = self::mountAttributes($attrs);

            $nodeStr =
                "<$el $atributes>
                $content
                </$el>";
        } else {
            $nodeStr = $content;
        }

        return $nodeStr;
    }

    private static function mountAttributes(array $attrs)
    {
        $data = '';
        foreach ($attrs as $attr => $value) {
            $data .= "$attr='$value'";
        }
        return $data;
    }

    public static function html($cb)
    {
        $ret = '';
        ob_start();
        $cb();
        $ret = ob_get_clean();
        return $ret;
    }

    public static function document($params, $cb)
    {
        $props = HTMLElement::getHTMLElementPoperties($params);
        $inner = "<!DOCTYPE html><html $props>%s</html>";
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

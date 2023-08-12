<?php

namespace Komodo\Exodus\Interfaces;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: IComponent.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

interface IComponent
{
    public static function create(array | string $params): array;
    public static function mount(array | string $params): string;
    public static function render(array | string $params): void;
}

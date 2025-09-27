<?php

namespace App\Services;

class IgrejaService
{
    private $igrejaModel;
    private $user;

    public const SITUATION_NEW  = 'sede';
    public const SITUATION_USED = 'filial';
}

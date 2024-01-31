<?php

namespace App\Controllers;

use App\Models\TipoAtendimento;

class TiposAtendimentosController
{
    function getAll(): array
    {
        $result = (new TipoAtendimento())->list();
        return $result;
    }
}

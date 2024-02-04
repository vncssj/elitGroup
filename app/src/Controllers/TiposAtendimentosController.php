<?php

namespace App\Controllers;

use App\Bootstrap\Controller;
use App\Models\TipoAtendimento;

class TiposAtendimentosController extends Controller
{
    function getAll(): array
    {
        $result = (new TipoAtendimento())->list();
        $this->router->response($result, 200);
        return $result;
    }
}

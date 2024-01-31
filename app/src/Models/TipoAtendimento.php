<?php

namespace App\Models;

use App\Bootstrap\Database;

class TipoAtendimento
{
    function list(): array
    {
        $db = new Database('elitgroup-db-1', 'elitgroup', 'elitgroup', 'elitgroup');
        $query = "SELECT * FROM tb_tipos_atendimento";
        $result = $db->query($query)->fetchAll();
        return $result;
    }
}

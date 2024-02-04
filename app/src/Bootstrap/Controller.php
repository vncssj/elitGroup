<?php

namespace App\Bootstrap;

class Controller
{
    protected $router;

    public function __construct($router = null)
    {
        $this->router = $router;
    }
}

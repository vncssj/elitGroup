<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Bootstrap\Router;

function dd($paramans)
{
    var_dump($paramans);
    die;
}

function getCurrentUri()
{
    $uri = $_SERVER['REQUEST_URI'];

    // Remove a parte da query string, se houver
    $uri = strtok($uri, '?');

    // Remove o prefixo do diretório, se estiver em um subdiretório
    $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $uri = str_replace($baseDir, '', $uri);

    return '/' . ltrim($uri, '/');
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = getCurrentUri();
header('Content-type: application/json');

$router = new Router();

require_once __DIR__ . '/src/routes.php';
$router->matchRoute("GET", $uri);

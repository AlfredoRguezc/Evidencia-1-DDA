<?php
/*
 |---------------------------------------------------------------------
 | index.php ajustado para InfinityFree
 |---------------------------------------------------------------------
 | Reemplaza el contenido de /htdocs/index.php con este archivo.
 |
 | Estructura esperada en InfinityFree:
 |   htdocs/
 |     index.php           <-- ESTE archivo
 |     .htaccess           <-- copiado de public/.htaccess
 |     favicon.ico
 |     robots.txt
 |     build/              <-- copiado de public/build (assets de Vite)
 |     storage/            <-- enlace simbolico publico (si lo usas)
 |     app/                <-- desde la raiz de Laravel
 |     bootstrap/
 |     config/
 |     database/
 |     resources/
 |     routes/
 |     storage/
 |     vendor/
 |     .env                <-- tu .env.production renombrado a .env
 |     artisan
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Modo mantenimiento (si existe)
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader de Composer (vendor esta junto a este index.php)
require __DIR__.'/vendor/autoload.php';

// Boot de Laravel (bootstrap esta junto a este index.php)
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());

<?php
/*
 * debug.php v2 - chequeos seguros sin bootear Laravel
 */
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(30);
ob_implicit_flush(true);

function section($title) {
    echo "\n\n=== $title ===\n";
    @ob_flush(); @flush();
}

header('Content-Type: text/plain; charset=utf-8');
echo "Halcon - Debug v2\n";
echo str_repeat('=', 60) . "\n";

// --- 1. Limpieza de basura backslash ---
section('1. Limpieza de archivos backslash');
$dir = __DIR__;
$cleaned = 0;
foreach (@scandir($dir) ?: [] as $e) {
    if ($e === '.' || $e === '..') continue;
    if (strpos($e, '\\') === false) continue;
    $path = $dir . '/' . $e;
    if (@unlink($path)) {
        echo "BORRADO: $e\n";
        $cleaned++;
    } else {
        echo "NO BORRO: $e (perms=" . substr(decoct(@fileperms($path)),-3) . ")\n";
    }
}
echo "Total borrados: $cleaned\n";

// --- 2. Estructura /htdocs/ ---
section('2. Contenido de /htdocs/');
$entries = @scandir($dir) ?: [];
foreach ($entries as $e) {
    if ($e === '.' || $e === '..') continue;
    $full = $dir . '/' . $e;
    $type = is_dir($full) ? 'DIR ' : 'FILE';
    $size = is_file($full) ? filesize($full) : '-';
    echo "$type  $e ($size)\n";
}

// --- 3. Estructura vendor/ ---
section('3. Contenido de vendor/');
$v = $dir . '/vendor';
if (!is_dir($v)) {
    echo "FALTA vendor/\n";
} else {
    foreach (@scandir($v) ?: [] as $e) {
        if ($e === '.' || $e === '..') continue;
        $full = $v . '/' . $e;
        $type = is_dir($full) ? 'DIR ' : 'FILE';
        echo "  $type  $e\n";
    }
    echo "\nvendor/autoload.php existe: " . (file_exists($v.'/autoload.php') ? 'SI' : 'NO') . "\n";
    echo "vendor/composer existe: " . (is_dir($v.'/composer') ? 'SI' : 'NO') . "\n";
}

// --- 4. Log de Laravel ---
section('4. storage/logs/laravel.log (ultimas 100 lineas)');
$logFile = $dir . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = @file($logFile);
    if ($lines === false) {
        echo "ERROR: no se pudo leer\n";
    } else {
        echo implode('', array_slice($lines, -100));
    }
} else {
    echo "NO existe " . $logFile . "\n";
    echo "(eso es esperable si Laravel nunca logro bootear)\n";
}

// --- 5. Permisos ---
section('5. Permisos de carpetas escribibles');
$paths = [
    'storage', 'storage/app', 'storage/framework', 'storage/framework/sessions',
    'storage/framework/cache', 'storage/framework/cache/data', 'storage/framework/views',
    'storage/logs', 'bootstrap/cache',
];
foreach ($paths as $p) {
    $full = $dir . '/' . $p;
    if (!file_exists($full)) {
        echo "FALTA       $p\n";
    } else {
        $w = is_writable($full) ? 'writable' : 'NO WRITABLE';
        $perms = substr(decoct(@fileperms($full)),-3);
        echo "$w  $p (perms=$perms)\n";
    }
}

// --- 6. .env ---
section('6. .env (passwords ocultos)');
$env = $dir . '/.env';
if (file_exists($env)) {
    $c = file_get_contents($env);
    $c = preg_replace('/(DB_PASSWORD=).+/', '$1***OCULTO***', $c);
    $c = preg_replace('/(APP_KEY=).+/', '$1***OCULTO***', $c);
    echo $c;
} else {
    echo "NO existe .env\n";
}

// --- 7. Test directo de BD MySQL (sin Laravel) ---
section('7. Test directo de conexion MySQL');
try {
    $envContents = file_get_contents($env);
    preg_match('/DB_HOST=(.+)/', $envContents, $h);
    preg_match('/DB_DATABASE=(.+)/', $envContents, $d);
    preg_match('/DB_USERNAME=(.+)/', $envContents, $u);
    preg_match('/DB_PASSWORD=(.+)/', $envContents, $pw);
    $host = trim($h[1] ?? '');
    $db   = trim($d[1] ?? '');
    $user = trim($u[1] ?? '');
    $pass = trim($pw[1] ?? '');
    echo "host=$host db=$db user=$user\n";
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Conexion MySQL OK\n";
    $cnt = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "Tabla users: $cnt registros\n";
    $cnt = $pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
    echo "Tabla roles: $cnt registros\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// --- 8. Entorno PHP ---
section('8. Info PHP');
echo "version: " . PHP_VERSION . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "open_basedir: " . (ini_get('open_basedir') ?: '(libre)') . "\n";
echo "Extensiones:\n";
foreach (['pdo','pdo_mysql','mbstring','openssl','tokenizer','xml','curl','json','fileinfo','zip'] as $ext) {
    echo "  $ext: " . (extension_loaded($ext) ? 'OK' : 'FALTA') . "\n";
}

echo "\n=== FIN ===\n";

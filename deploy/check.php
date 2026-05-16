<?php
header('Content-Type: text/plain; charset=utf-8');
echo "STEP 0 - script iniciado\n"; @flush();

$dir = __DIR__;
echo "DIR: $dir\n"; @flush();

echo "STEP 1 - listando htdocs/...\n"; @flush();
foreach (scandir($dir) as $e) {
    if ($e === '.' || $e === '..') continue;
    $type = is_dir("$dir/$e") ? 'D' : 'F';
    echo "  $type $e\n";
}
@flush();

echo "\nSTEP 2 - checando archivos criticos...\n"; @flush();
foreach (['.env','index.php','vendor/autoload.php','bootstrap/app.php'] as $f) {
    echo "  $f: " . (file_exists("$dir/$f") ? 'OK' : 'FALTA') . "\n";
}
@flush();

echo "\nSTEP 3 - leyendo .env...\n"; @flush();
$env = "$dir/.env";
if (file_exists($env)) {
    $c = file_get_contents($env);
    $c = preg_replace('/(DB_PASSWORD=).+/', '$1***', $c);
    $c = preg_replace('/(APP_KEY=).+/', '$1***', $c);
    echo $c;
}
@flush();

echo "\nSTEP 4 - leyendo log de Laravel si existe...\n"; @flush();
$log = "$dir/storage/logs/laravel.log";
if (file_exists($log)) {
    echo "Tamano: " . filesize($log) . " bytes\n";
    $lines = file($log);
    echo "Ultimas 50 lineas:\n";
    echo implode('', array_slice($lines, -50));
} else {
    echo "No existe el log\n";
}
@flush();

echo "\nSTEP 5 - FIN\n";

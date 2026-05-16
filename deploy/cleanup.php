<?php
header('Content-Type: text/plain');
$dir = __DIR__;
foreach (scandir($dir) as $e) {
    if (strpos($e, '\\') !== false) {
        if (@unlink("$dir/$e")) echo "borrado: $e\n";
        else echo "no borro: $e\n";
    }
}
// limpiar storage/logs/laravel.log para empezar limpio
$log = "$dir/storage/logs/laravel.log";
if (file_exists($log)) {
    file_put_contents($log, '');
    echo "log limpiado\n";
}
echo "FIN\n";

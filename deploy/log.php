<?php
header('Content-Type: text/plain; charset=utf-8');
$log = __DIR__ . '/storage/logs/laravel.log';
if (!file_exists($log)) {
    echo "No existe el log\n";
    exit;
}
$size = filesize($log);
echo "Log size: $size bytes\n";
echo str_repeat('=', 60) . "\n";
$content = file_get_contents($log);
// Mostrar las primeras 4000 chars (donde estan los mensajes de error)
echo substr($content, 0, 4000);
echo "\n\n[... resto truncado, total $size bytes]\n";

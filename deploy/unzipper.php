<?php
/*
 * Unzipper robusto para Halcon - extrae entrada por entrada
 * y crea directorios manualmente con mkdir recursivo.
 */

set_time_limit(0);
ini_set('memory_limit', '512M');
ini_set('display_errors', '1');
error_reporting(E_ALL);

$zipFile = __DIR__ . '/htdocs_staging.zip';
$destDir = __DIR__;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unzipper Halcon v2</title>
    <style>
        body { font-family: -apple-system, Segoe UI, sans-serif; background:#1a1a1a; color:#eee; padding:40px; max-width:1100px; margin:auto; }
        h1 { color:#4ade80; }
        h2 { color:#60a5fa; }
        .box { background:#222; padding:20px; border-radius:8px; margin:20px 0; border:1px solid #333; }
        .ok { color:#4ade80; }
        .err { color:#f87171; }
        .warn { color:#fbbf24; }
        .info { color:#60a5fa; }
        button, .btn { display:inline-block; background:#4ade80; color:#000; border:0; padding:12px 24px; border-radius:6px; font-weight:bold; cursor:pointer; text-decoration:none; font-size:16px; }
        pre { background:#000; padding:15px; border-radius:6px; max-height:500px; overflow:auto; font-size:11px; line-height:1.4; }
        code { background:#000; padding:2px 6px; border-radius:3px; }
        ul { line-height:1.8; }
    </style>
</head>
<body>
    <h1>Unzipper Halcon v2 (robusto)</h1>

<?php

echo '<div class="box info">';
echo '<h2>Diagnostico del entorno</h2>';
echo '<ul>';
echo '<li>PHP: ' . PHP_VERSION . '</li>';
echo '<li>__DIR__: <code>' . htmlspecialchars(__DIR__) . '</code></li>';
echo '<li>Carpeta escribible: ' . (is_writable(__DIR__) ? '<span class="ok">SI</span>' : '<span class="err">NO</span>') . '</li>';
echo '<li>ZipArchive disponible: ' . (class_exists('ZipArchive') ? '<span class="ok">SI</span>' : '<span class="err">NO</span>') . '</li>';
echo '<li>open_basedir: <code>' . (ini_get('open_basedir') ?: '(no restringido)') . '</code></li>';
$df = @disk_free_space(__DIR__);
echo '<li>Espacio libre: ' . ($df === false ? 'desconocido' : round($df/1024/1024,1).' MB') . '</li>';
echo '</ul>';
echo '</div>';

if (!file_exists($zipFile)) {
    echo '<div class="box err"><p>No se encontro <code>htdocs_staging.zip</code> en esta carpeta.</p></div>';
    echo '</body></html>';
    exit;
}

$zipSize = round(filesize($zipFile) / 1024 / 1024, 2);
echo '<div class="box info"><p><strong>ZIP encontrado:</strong> ' . $zipSize . ' MB</p></div>';

if (!isset($_GET['go'])) {
    echo '<div class="box">';
    echo '<p>Esta version extrae entrada por entrada, creando directorios con <code>mkdir</code> explicito y reportando cualquier fallo.</p>';
    echo '<a class="btn" href="?go=1">Extraer (modo robusto)</a>';
    echo '</div>';
    echo '</body></html>';
    exit;
}

$zip = new ZipArchive();
$res = $zip->open($zipFile);
if ($res !== true) {
    echo '<div class="box err"><p>Error al abrir ZIP. Codigo: ' . $res . '</p></div>';
    echo '</body></html>';
    exit;
}

$total = $zip->numFiles;
echo '<div class="box info"><p>Extrayendo <strong>' . $total . '</strong> entradas una por una...</p></div>';
@ob_flush(); @flush();

$okFiles = 0;
$okDirs = 0;
$errors = [];
$start = microtime(true);

for ($i = 0; $i < $total; $i++) {
    $name = $zip->getNameIndex($i);
    if ($name === false) { $errors[] = "Indice $i: nombre invalido"; continue; }

    // Normalizar y proteger contra path traversal
    $safe = str_replace(['..', "\0"], '', $name);
    $target = $destDir . '/' . $safe;

    // Entrada de directorio (termina con /)
    if (substr($name, -1) === '/') {
        if (!is_dir($target)) {
            if (!@mkdir($target, 0755, true)) {
                $errors[] = "mkdir fallo: $safe";
            } else {
                $okDirs++;
            }
        } else {
            $okDirs++;
        }
        continue;
    }

    // Asegurar que el directorio padre exista
    $parent = dirname($target);
    if (!is_dir($parent)) {
        if (!@mkdir($parent, 0755, true)) {
            $errors[] = "mkdir parent fallo: $safe";
            continue;
        }
    }

    // Extraer archivo
    $stream = $zip->getStream($name);
    if (!$stream) {
        $errors[] = "getStream nulo: $safe";
        continue;
    }
    $contents = stream_get_contents($stream);
    fclose($stream);

    if (file_put_contents($target, $contents) === false) {
        $errors[] = "write fallo: $safe";
    } else {
        $okFiles++;
    }
}
$zip->close();
$elapsed = round(microtime(true) - $start, 2);

echo '<div class="box ok">';
echo '<h2>Resumen</h2>';
echo '<ul>';
echo '<li>Archivos extraidos: <strong>' . $okFiles . '</strong></li>';
echo '<li>Directorios creados: <strong>' . $okDirs . '</strong></li>';
echo '<li>Errores: <strong>' . count($errors) . '</strong></li>';
echo '<li>Tiempo: ' . $elapsed . ' s</li>';
echo '</ul>';
echo '</div>';

if (count($errors) > 0) {
    echo '<div class="box err">';
    echo '<h3>Errores (primeros 30):</h3>';
    echo '<pre>' . htmlspecialchars(implode("\n", array_slice($errors, 0, 30))) . '</pre>';
    echo '</div>';
}

// Verificacion
echo '<div class="box">';
echo '<h3>Verificacion de estructura:</h3>';
$expected = ['app','bootstrap','build','config','database','resources','routes','storage','vendor','index.php','.htaccess','.env','artisan'];
echo '<ul>';
foreach ($expected as $item) {
    $path = __DIR__ . '/' . $item;
    if (is_dir($path)) {
        $count = @count(@scandir($path)) - 2;
        echo '<li class="ok">' . htmlspecialchars($item) . '/ OK (' . $count . ' items)</li>';
    } elseif (is_file($path)) {
        echo '<li class="ok">' . htmlspecialchars($item) . ' OK (archivo)</li>';
    } else {
        echo '<li class="err">' . htmlspecialchars($item) . ' FALTA</li>';
    }
}
echo '</ul>';
echo '</div>';

echo '<div class="box info">';
echo '<h3>Siguientes pasos:</h3>';
echo '<ol>';
echo '<li>Si todo dice OK: <strong>borra</strong> este archivo y <code>htdocs_staging.zip</code>.</li>';
echo '<li>Abre <a href="/" style="color:#4ade80">la pagina principal</a> para probar Halcon.</li>';
echo '</ol>';
echo '</div>';
?>
</body>
</html>

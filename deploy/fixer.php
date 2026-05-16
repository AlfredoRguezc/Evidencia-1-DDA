<?php
/*
 * Fixer: reorganiza archivos con backslash en su nombre a la estructura
 * correcta de carpetas. Subelo a /htdocs/ y visitalo en el navegador.
 */
set_time_limit(0);
ini_set('memory_limit', '512M');
ini_set('display_errors', '1');
error_reporting(E_ALL);

$dir = __DIR__;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fixer Halcon</title>
    <style>
        body { font-family: -apple-system, Segoe UI, sans-serif; background:#1a1a1a; color:#eee; padding:40px; max-width:1100px; margin:auto; }
        h1 { color:#4ade80; }
        h2 { color:#60a5fa; }
        .box { background:#222; padding:20px; border-radius:8px; margin:20px 0; border:1px solid #333; }
        .ok { color:#4ade80; } .err { color:#f87171; } .warn { color:#fbbf24; }
        .btn { display:inline-block; background:#4ade80; color:#000; border:0; padding:12px 24px; border-radius:6px; font-weight:bold; cursor:pointer; text-decoration:none; font-size:16px; }
        pre { background:#000; padding:15px; border-radius:6px; max-height:400px; overflow:auto; font-size:11px; }
    </style>
</head>
<body>
<h1>Fixer - reorganizar archivos con backslash</h1>

<?php

// Contar archivos con backslash
$bad = 0;
$entries = scandir($dir);
foreach ($entries as $e) {
    if ($e === '.' || $e === '..') continue;
    if (strpos($e, '\\') !== false && is_file($dir.'/'.$e)) $bad++;
}

echo '<div class="box">';
echo '<p><strong>'.$bad.'</strong> archivos con backslash detectados en <code>'.htmlspecialchars($dir).'</code></p>';
echo '</div>';

if ($bad === 0 && !isset($_GET['go'])) {
    echo '<div class="box ok"><p>No hay archivos para reorganizar. Verifica si la estructura ya esta correcta abajo.</p></div>';
}

if (!isset($_GET['go'])) {
    if ($bad > 0) {
        echo '<div class="box"><a class="btn" href="?go=1">Reorganizar '.$bad.' archivos</a></div>';
    }
} else {
    $moved = 0; $errors = [];
    $start = microtime(true);
    $batch = 0;

    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        if (strpos($entry, '\\') === false) continue;

        $oldPath = $dir . '/' . $entry;
        if (!is_file($oldPath)) continue;

        $newRel = str_replace('\\', '/', $entry);
        $newPath = $dir . '/' . $newRel;

        $parent = dirname($newPath);
        if (!is_dir($parent)) {
            if (!@mkdir($parent, 0755, true)) {
                $errors[] = "mkdir fallo: $parent";
                continue;
            }
        }

        if (@rename($oldPath, $newPath)) {
            $moved++;
        } else {
            $errors[] = "rename fallo: $entry";
        }
        $batch++;
    }

    $elapsed = round(microtime(true) - $start, 2);

    echo '<div class="box ok">';
    echo '<h2>Resultado</h2>';
    echo '<ul>';
    echo '<li>Movidos: <strong>'.$moved.'</strong></li>';
    echo '<li>Errores: <strong>'.count($errors).'</strong></li>';
    echo '<li>Tiempo: '.$elapsed.' s</li>';
    echo '</ul>';
    if (count($errors) > 0) {
        echo '<details><summary>Ver errores</summary><pre>'.htmlspecialchars(implode("\n", array_slice($errors,0,50))).'</pre></details>';
    }
    echo '</div>';

    // Re-contar
    $remaining = 0;
    foreach (scandir($dir) as $e) {
        if (strpos($e, '\\') !== false && is_file($dir.'/'.$e)) $remaining++;
    }
    if ($remaining > 0) {
        echo '<div class="box warn">';
        echo '<p>Aun quedan '.$remaining.' archivos por reorganizar (probablemente se agoto el tiempo).</p>';
        echo '<a class="btn" href="?go=1">Continuar reorganizacion</a>';
        echo '</div>';
    }
}

// Verificacion siempre visible
echo '<div class="box">';
echo '<h3>Estado actual de la estructura:</h3>';
$expected = ['app','bootstrap','build','config','database','resources','routes','storage','vendor','index.php','.htaccess','.env','artisan','favicon.ico','robots.txt'];
echo '<ul>';
foreach ($expected as $item) {
    $path = $dir . '/' . $item;
    if (is_dir($path)) {
        $count = @count(@scandir($path)) - 2;
        echo '<li class="ok">'.htmlspecialchars($item).'/ OK ('.$count.' items)</li>';
    } elseif (is_file($path)) {
        echo '<li class="ok">'.htmlspecialchars($item).' OK</li>';
    } else {
        echo '<li class="err">'.htmlspecialchars($item).' FALTA</li>';
    }
}
echo '</ul>';
echo '</div>';

?>

<div class="box">
    <h3>Cuando todo este OK:</h3>
    <ol>
        <li>Borra <code>fixer.php</code></li>
        <li>Borra <code>unzipper.php</code> (si sigue ahi)</li>
        <li>Borra <code>htdocs_staging.zip</code></li>
        <li>Abre <a href="/" style="color:#4ade80">la pagina principal</a> para probar Halcon</li>
    </ol>
</div>
</body>
</html>

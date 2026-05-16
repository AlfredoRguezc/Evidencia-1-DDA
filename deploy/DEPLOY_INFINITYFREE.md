# Despliegue Halcon en InfinityFree

Esta guia describe paso a paso como publicar el proyecto Laravel en InfinityFree
para entregarlo al revisor. Cuando termines tendras un enlace publico tipo:

    https://halcon.infinityfreeapp.com

---

## 1. Crear cuenta y sitio en InfinityFree

1. Ve a https://infinityfree.com/ y crea una cuenta (gratis, no pide tarjeta).
2. En el panel de control da clic en **"Create Account"** (Crear cuenta nueva).
3. Elige un subdominio gratis, por ejemplo `halcon.infinityfreeapp.com`.
4. Espera 2-5 minutos a que se aprovisione el hosting.

Al final tendras 4 datos importantes que vas a usar:
- Subdominio publico (URL)
- FTP host, user y password
- Datos de phpMyAdmin
- Cuando crees la BD MySQL: host, nombre BD, usuario, password

---

## 2. Crear la base de datos MySQL

1. En el panel de InfinityFree -> **"MySQL Databases"**.
2. Da nombre a la BD, por ejemplo `halcon`. El sistema le agregara prefijo y
   quedara algo como `if0_12345678_halcon`.
3. Anota: **DB_HOST** (algo como `sql123.infinityfree.com`), **DB_DATABASE**,
   **DB_USERNAME**, **DB_PASSWORD**.

### Importar el esquema

1. En el panel da clic en **"PhpMyAdmin"** junto a tu BD.
2. Selecciona la BD a la izquierda.
3. Pestania **"Importar"** -> elige el archivo
   `deploy/halcon_schema.sql` de este proyecto -> **"Continuar"**.
4. Debes ver "Importacion finalizada con exito" y las 11 tablas creadas.

> Usuario admin que deja precargado el SQL:
>  - email: `admin@halcon.com`
>  - password: `password123`

---

## 3. Configurar `.env` para produccion

1. Abre `deploy/.env.production` (en la raiz del proyecto, archivo
   `.env.production`).
2. Edita las 5 lineas marcadas:
   - `APP_URL=https://TU-SUBDOMINIO.infinityfreeapp.com`
   - `DB_HOST=sqlXXX.infinityfree.com`         (del paso 2)
   - `DB_DATABASE=if0_XXXXXXXX_halcon`         (del paso 2)
   - `DB_USERNAME=if0_XXXXXXXX`                (del paso 2)
   - `DB_PASSWORD=...`                         (del paso 2)
3. Guarda el archivo.
4. **Renombra `.env.production` a `.env`** justo antes de subirlo (es el archivo
   que Laravel busca). Conserva una copia local con el nombre original.

---

## 4. Subir archivos por FTP

Recomendado: **FileZilla** (https://filezilla-project.org/).

### Datos de conexion

- **Host**: el que te dio InfinityFree (ej. `ftpupload.net`)
- **Usuario** y **password**: los del panel
- **Puerto**: 21

### Estructura final en `htdocs/`

Una vez conectado, abre la carpeta `htdocs/` del servidor. Tienes que llegar a
esta estructura final:

```
htdocs/
├── index.php             <- usar deploy/htdocs_index.php (renombrado a index.php)
├── .htaccess             <- usar deploy/htdocs_htaccess.txt (renombrado a .htaccess)
├── .env                  <- tu .env.production (renombrado a .env)
├── favicon.ico           <- copia de public/favicon.ico
├── robots.txt            <- copia de public/robots.txt
├── build/                <- copia de public/build/    (assets compilados)
├── storage/              <- copia de public/storage si existe (puede no existir)
├── app/                  <- de la raiz del proyecto
├── bootstrap/            <- de la raiz del proyecto
├── config/               <- de la raiz del proyecto
├── database/             <- de la raiz del proyecto
├── resources/            <- de la raiz del proyecto
├── routes/               <- de la raiz del proyecto
├── storage/              <- de la raiz del proyecto (sobreescribe el otro)
├── vendor/               <- de la raiz del proyecto (ESTA ES LA CARPETA GRANDE)
└── artisan               <- opcional
```

### Subida en orden

1. **Primero los archivos chicos**: `index.php`, `.htaccess`, `.env`,
   `favicon.ico`, `robots.txt`. Usar los archivos preparados en `deploy/`.
2. **Las carpetas de codigo**: `app/`, `bootstrap/`, `config/`, `database/`,
   `resources/`, `routes/`, `storage/`. Son rapidas.
3. **`build/`** (de `public/build/`). Pequena.
4. **`vendor/`**. Es la carpeta mas grande (miles de archivos). FileZilla puede
   tardar bastante. Si se corta, retomalo con "Queue".

### IMPORTANTE sobre `vendor/`

Antes de subir vendor/, asegurate de que NO tiene paquetes dev. Desde tu PC:

```powershell
composer install --no-dev --optimize-autoloader
```

(Ya se ejecuto al preparar este despliegue.)

Si en algun momento corres `composer install` sin `--no-dev`, vuelvelo a correr
con `--no-dev` antes de subir.

---

## 5. Permisos

InfinityFree usa permisos 644 para archivos y 755 para carpetas por defecto.
Generalmente no hay que tocar nada, pero si Laravel tira error de escritura:

1. En FileZilla, click derecho sobre `storage/` -> **"Permisos de archivo"**
   -> valor numerico **`755`**, marca "Aplicar a subdirectorios".
2. Repite con `bootstrap/cache/`.

---

## 6. Verificar

1. Abre en el navegador: `https://TU-SUBDOMINIO.infinityfreeapp.com`
2. Debe cargar la pagina de bienvenida o el login.
3. Entra con `admin@halcon.com / password123`.
4. Verifica que la BD responde (crea un pedido, etc.).

### Si ves "500 Server Error"

- Pon temporalmente `APP_DEBUG=true` en `.env` para ver el error.
- Errores tipicos:
  - **"could not find driver"** -> en InfinityFree pdo_mysql viene por
    defecto, no deberia pasar. Si pasa, revisa que `DB_CONNECTION=mysql`.
  - **"SQLSTATE[HY000] [2002]"** -> revisa `DB_HOST`, debe ser el host
    completo `sqlXXX.infinityfree.com`, no `localhost`.
  - **"No application encryption key"** -> revisa que `APP_KEY` este en `.env`.
  - **"The stream or file ... could not be opened"** -> permisos de `storage/`.
- Una vez funcione, vuelve a poner `APP_DEBUG=false`.

### Si ves la pagina pero sin estilos

Significa que `build/` no se subio o quedo en mala ruta. Verifica que existe
`htdocs/build/manifest.json`.

---

## 7. Entregar al revisor

Una vez que la app este funcionando, los enlaces que pide el revisor son:

- **Aplicacion desplegada**: `https://TU-SUBDOMINIO.infinityfreeapp.com`
- **phpMyAdmin (backend BD)**: el panel publico de InfinityFree.
  Comparte con el revisor las credenciales SOLO si es necesario, o explicale
  que la base esta hospedada en InfinityFree (MySQL) y que el panel de gestion
  de BD es phpMyAdmin del mismo host.

Sugerencia: agrega estos enlaces al README.md del proyecto en una seccion
"Despliegue".

---

## Notas importantes

- **Plan gratuito de InfinityFree**: bloquea conexiones MySQL remotas. Por eso
  no es posible correr `php artisan migrate` desde tu PC apuntando a la BD
  remota; hay que importar el SQL via phpMyAdmin como en el paso 2.
- **Queues**: estan desactivadas (`QUEUE_CONNECTION=sync`) porque InfinityFree
  no permite procesos en background.
- **Sesiones**: usan archivos en disco (`SESSION_DRIVER=file`) en lugar de BD
  para reducir carga.
- **Subida de archivos**: si tu app sube fotos de evidencia, asegurate de que
  la carpeta destino tiene permisos 755 y `FILESYSTEM_DISK=local`. Para
  servirlas publicamente, ejecuta el equivalente de `php artisan storage:link`
  manualmente, copiando `storage/app/public/` a `htdocs/storage/`.
- **Limites del plan gratuito**: 5GB de espacio, ancho de banda "ilimitado"
  pero con throttling. Suficiente para una entrega academica.

---

## Archivos generados en `deploy/`

| Archivo                  | Que es                                                |
|--------------------------|-------------------------------------------------------|
| `halcon_schema.sql`      | Esquema MySQL + admin + roles. Importar en phpMyAdmin |
| `htdocs_index.php`       | index.php ajustado. Renombrar a `index.php` en htdocs |
| `htdocs_htaccess.txt`    | .htaccess endurecido. Renombrar a `.htaccess`         |
| `DEPLOY_INFINITYFREE.md` | Esta guia                                             |

El `.env.production` esta en la raiz del proyecto, no en `deploy/`.

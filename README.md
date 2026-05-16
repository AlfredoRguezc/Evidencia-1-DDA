# Sistema de Control de Entregas - Distribuidora Halcon

Este proyecto es la parte programada (Evidencia 2) para la distribuidora de materiales Halcon. El sistema permite gestionar todo el proceso de ventas y entrega de material, asegurando que el cliente siempre sepa dónde está su pedido.

## Despliegue en línea

- **Aplicación desplegada (frontend + backend Laravel)**:
  https://evidenciafinal.lovestoblog.com

- **Hosting**: InfinityFree (PHP 8.3 + MySQL)
- **Base de datos**: MySQL en `sql211.infinityfree.com` (administrable vía phpMyAdmin desde el panel de InfinityFree).

### Credenciales de prueba

- **Email:** `admin@halcon.com`
- **Password:** `password123`

---

## Lo que hice en esta Evidencia (LO2):

### 1. Modelos y Relaciones
- Creé los modelos **User, Role, Pedido y Evidencia**.
- Configuré las relaciones para que todo esté conectado: un usuario tiene un rol (departamento), un pedido pertenece a un usuario y un pedido puede tener varias fotos de evidencia.

### 2. Base de Datos (Migraciones)
- Diseñé las tablas con sus llaves primarias y foráneas para que no haya errores de datos.
- Implementé el **Soft Delete (Borrado Lógico)** para que, al "borrar" una orden, esta se guarde en una sección de archivados y se pueda recuperar después.

### 3. Controladores y Rutas
- Programé el **PedidoController** y el **UserController** con todas sus funciones.
- Todas las rutas administrativas están protegidas para que solo el personal registrado pueda entrar.

### 4. Vistas (Frontend)
- **Para Clientes:** Creé una página de inicio con un buscador de facturas. Si el pedido ya se entregó, el sistema muestra la foto de la evidencia.
- **Para el Personal:** Un Dashboard con acceso a:
    - Lista de Órdenes (ordenadas de la más nueva a la más vieja).
    - Creación y actualización de pedidos con subida de fotos.
    - Gestión de usuarios (activar/desactivar personal y asignar departamentos).
    - Sección de Archivados para recuperar órdenes borradas.

## Cómo correr el proyecto:

1. **Instalar todo:** Ejecutar `composer install` y `npm install`.
2. **Base de Datos:** Correr `php artisan migrate --seed` para crear las tablas y los usuarios de prueba.
3. **Fotos:** Muy importante correr `php artisan storage:link` para que las fotos de evidencia se puedan ver.
4. **Servidor:** Correr `php artisan serve` y en otra terminal `npm run dev`.

**Cuenta de Administrador para pruebas:**
- **Correo:** admin@halcon.com
- **Contraseña:** password123

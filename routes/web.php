<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. Rutas Públicas (Para clientes sin registro)
Route::get('/', function () {
    return view('welcome');
});

// Ruta de búsqueda para el cliente (Consultar estado y fotos)
Route::get('/consultar', [PedidoController::class, 'search'])->name('pedidos.search');

// 2. Rutas Protegidas (Requieren Login)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas para el Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Pedidos (CRUD Completo y Lógica de Negocio)
    Route::resource('pedidos', PedidoController::class);

    // Rutas para Borrado Lógico (Archivados y Restaurar)
    Route::get('pedidos-archivados', [PedidoController::class, 'archived'])->name('pedidos.archived');
    Route::patch('pedidos/{id}/restore', [PedidoController::class, 'restore'])->name('pedidos.restore');

    // Rutas para Gestión de Usuarios (Admin: Activos/Inactivos y Roles)
    Route::resource('users', UserController::class);

});

require __DIR__ . '/auth.php';
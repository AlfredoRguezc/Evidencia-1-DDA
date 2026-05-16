<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Administrador', 'Ventas', 'Compras', 'Almacen', 'Ruta'];
        foreach ($roles as $rol) {
            \App\Models\Role::create(['nombre_departamento' => $rol]);
        }
    }
}

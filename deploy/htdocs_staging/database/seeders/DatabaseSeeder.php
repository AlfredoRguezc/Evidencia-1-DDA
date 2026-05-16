<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ejecutamos primero el RoleSeeder para que los roles existan en la BD
        $this->call(RoleSeeder::class);

        // 2. Buscamos el rol de Administrador para asignárselo al usuario inicial
        $adminRole = Role::where('nombre_departamento', 'Administrador')->first();

        // 3. Creamos el usuario administrativo
        User::factory()->create([
            'name' => 'Alfredo Admin',
            'email' => 'admin@halcon.com',
            'password' => Hash::make('password123'), // Contraseña segura y hasheada
            'role_id' => $adminRole->id, // Evita el error de NOT NULL constraint
            'activo' => true,
        ]);

        // Opcional: Crear usuarios de prueba para otros departamentos
        /*
        $ventasRole = Role::where('nombre_departamento', 'Ventas')->first();
        User::factory()->create([
            'name' => 'Vendedor Prueba',
            'email' => 'ventas@halcon.com',
            'password' => Hash::make('password123'),
            'role_id' => $ventasRole->id,
            'activo' => true,
        ]);
        */
    }
}
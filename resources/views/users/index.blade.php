<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gestión de Usuarios') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow sm:rounded-lg">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold">Lista de Personal</h3>
                <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Nuevo
                    Usuario</a>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Departamento</th>
                        <th class="text-left">Estatus</th>
                        <th class="text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->nombre_departamento }}</td>
                            <td>
                                <span class="{{ $user->activo ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td><a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
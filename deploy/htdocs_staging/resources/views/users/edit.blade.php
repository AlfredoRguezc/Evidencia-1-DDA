<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow sm:rounded-lg">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 gap-4">
                    <x-text-input name="name" :value="$user->name" required />
                    <x-text-input name="email" :value="$user->email" required />
                    <select name="role_id" class="rounded-md border-gray-300">
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ $user->role_id == $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre_departamento }}</option>
                        @endforeach
                    </select>
                    <select name="activo" class="rounded-md border-gray-300">
                        <option value="1" {{ $user->activo ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$user->activo ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    <x-primary-button>Actualizar Usuario</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
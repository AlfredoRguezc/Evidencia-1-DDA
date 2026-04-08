<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actualizar Estado de Orden: ') }} {{ $pedido->factura_num }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p><strong>Cliente:</strong> {{ $pedido->nombre_cliente }}</p>
                            <p><strong>Dirección:</strong> {{ $pedido->direccion_entrega }}</p>
                        </div>

                        <div>
                            <x-input-label for="estado" :value="__('Estado del Pedido')" />
                            <select name="estado" id="estado"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Ordered" {{ $pedido->estado == 'Ordered' ? 'selected' : '' }}>Ordered
                                </option>
                                <option value="In process" {{ $pedido->estado == 'In process' ? 'selected' : '' }}>In
                                    process</option>
                                <option value="In route" {{ $pedido->estado == 'In route' ? 'selected' : '' }}>In route
                                    (Requiere Foto Carga)</option>
                                <option value="Delivered" {{ $pedido->estado == 'Delivered' ? 'selected' : '' }}>Delivered
                                    (Requiere Foto Entrega)</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="foto" :value="__('Subir Foto de Evidencia (Carga/Entrega)')" />
                            <input type="file" name="foto" id="foto"
                                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500">Obligatorio para estados 'In route' y 'Delivered'.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('pedidos.index') }}"
                            class="mr-4 text-gray-600 hover:text-gray-900">Cancelar</a>
                        <x-primary-button>
                            {{ __('Actualizar Pedido') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
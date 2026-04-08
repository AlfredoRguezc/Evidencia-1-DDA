<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Pedido: ') }} {{ $pedido->factura_num }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-bold mb-4">Información del Cliente</h3>
                        <p><strong>Nombre/Razón Social:</strong> {{ $pedido->nombre_cliente }}</p>
                        <p><strong>Número de Cliente:</strong> {{ $pedido->numero_cliente_unico }}</p>
                        <p><strong>Dirección de Entrega:</strong> {{ $pedido->direccion_entrega }}</p>
                        <p><strong>Datos Fiscales:</strong> {{ $pedido->datos_fiscales }}</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-4">Estado del Pedido</h3>
                        <p><strong>Estado Actual:</strong>
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">{{ $pedido->estado }}</span>
                        </p>
                        <p><strong>Registrado por:</strong> {{ $pedido->usuario->name }}</p>
                        <p><strong>Fecha de Creación:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Notas Extra:</strong> {{ $pedido->notas_extra ?? 'Sin notas' }}</p>
                    </div>

                    <div class="md:col-span-2 mt-8 border-t pt-6">
                        <h3 class="text-lg font-bold mb-4">Evidencias Fotográficas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($pedido->evidencias as $evidencia)
                                <div class="border rounded p-2 text-center">
                                    <p class="font-semibold mb-2">Evidencia de {{ $evidencia->tipo_evidencia }}</p>
                                    <img src="{{ asset('storage/' . $evidencia->url_foto) }}" class="mx-auto rounded shadow"
                                        style="max-height: 300px;">
                                    <p class="text-xs text-gray-500 mt-2">Subida el:
                                        {{ $evidencia->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500">No se han cargado evidencias para este pedido aún.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('pedidos.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Orden') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('pedidos.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="factura_num" :value="__('Número de Factura')" />
                            <x-text-input id="factura_num" name="factura_num" type="text" class="mt-1 block w-full"
                                required />
                        </div>

                        <div>
                            <x-input-label for="numero_cliente_unico" :value="__('Número Único de Cliente')" />
                            <x-text-input id="numero_cliente_unico" name="numero_cliente_unico" type="text"
                                class="mt-1 block w-full" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="nombre_cliente" :value="__('Nombre o Razón Social')" />
                            <x-text-input id="nombre_cliente" name="nombre_cliente" type="text"
                                class="mt-1 block w-full" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="datos_fiscales" :value="__('Datos Fiscales')" />
                            <textarea id="datos_fiscales" name="datos_fiscales"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="3" required></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="direccion_entrega" :value="__('Dirección de Entrega')" />
                            <x-text-input id="direccion_entrega" name="direccion_entrega" type="text"
                                class="mt-1 block w-full" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notas_extra" :value="__('Notas o Información Extra')" />
                            <textarea id="notas_extra" name="notas_extra"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="2"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button>
                            {{ __('Levantar Pedido') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halcon - Consulta de Pedidos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">

        <div class="absolute top-6 right-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 underline hover:text-black">Acceso Personal
                Halcon</a>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md text-center">
            <h1 class="text-2xl font-bold text-red-600 mb-4">HALCON</h1>
            <h2 class="text-lg text-gray-700 mb-6">Consulta el estado de tu pedido</h2>

            <form action="{{ route('pedidos.search') }}" method="GET" class="space-y-4">
                <div>
                    <input type="text" name="factura_num" placeholder="Ingresa número de factura" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-center">
                </div>
                <button type="submit"
                    class="w-full bg-red-600 text-white font-bold py-2 rounded-md hover:bg-red-700 transition">
                    Consultar estatus
                </button>
            </form>

            @if(isset($pedido))
                <div class="mt-8 p-4 bg-gray-50 rounded-md border border-gray-200">
                    <h3 class="font-bold text-gray-800">Resultado para: {{ $pedido->factura_num }}</h3>
                    <p class="text-lg mt-2">Estado:
                        <span class="font-bold text-blue-600">{{ $pedido->estado }}</span>
                    </p>
                    <p class="text-sm text-gray-600">Cliente: {{ $pedido->nombre_cliente }}</p>

                    @if($pedido->evidencias->count() > 0)
                        <div class="mt-4">
                            <p class="text-xs font-semibold mb-2">Fotografía de evidencia:</p>
                            @foreach($pedido->evidencias as $evidencia)
                                <img src="{{ asset('storage/' . $evidencia->url_foto) }}"
                                    class="rounded shadow-md mx-auto max-h-48">
                            @endforeach
                        </div>
                    @endif
                </div>
            @elseif(request()->has('factura_num'))
                <p class="mt-6 text-red-500 font-semibold italic">No se encontró información para esa factura.</p>
            @endif
        </div>


    </div>
</body>

</html>
<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    // Listado general ordenado del último al primero
    public function index()
    {
        $pedidos = Pedido::orderBy('created_at', 'desc')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    // Muestra el formulario para crear un pedido
    public function create()
    {
        return view('pedidos.create');
    }

    // Guardar pedido con los datos fiscales y dirección
    public function store(Request $request)
    {
        $request->validate([
            'factura_num' => 'required|unique:pedidos',
            'numero_cliente_unico' => 'required',
            'nombre_cliente' => 'required',
            'direccion_entrega' => 'required',
        ]);

        Pedido::create($request->all() + ['user_id_registro' => Auth::id(), 'estado' => 'Ordered']);
        return redirect()->route('pedidos.index');
    }

    // Muestra el detalle de un pedido (Fotos y datos)
    public function show(Pedido $pedido)
    {
        $pedido->load(['evidencias', 'usuario']);
        return view('pedidos.show', compact('pedido'));
    }

    // Muestra el formulario para editar estado y subir fotos
    public function edit(Pedido $pedido)
    {
        return view('pedidos.edit', compact('pedido'));
    }

    // Lógica para actualizar estado y subir fotos (Ruta/Almacén)
    public function update(Request $request, Pedido $pedido)
    {
        $pedido->estado = $request->estado;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('evidencias', 'public');
            Evidencia::create([
                'pedido_id' => $pedido->id,
                'url_foto' => $path,
                'tipo_evidencia' => $request->estado
            ]);
        }

        $pedido->save();
        return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado');
    }

    // Borrado lógico para archivar
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return redirect()->route('pedidos.index');
    }

    // Ver pedidos archivados
    public function archived()
    {
        $pedidos = Pedido::onlyTrashed()->get();
        return view('pedidos.archived', compact('pedidos'));
    }

    // Restaurar pedido archivado
    public function restore($id)
    {
        Pedido::onlyTrashed()->find($id)->restore();
        return redirect()->route('pedidos.archived');
    }

    // Método para la búsqueda pública del cliente
    public function search(Request $request)
    {
        $pedido = Pedido::with('evidencias')
            ->where('factura_num', $request->factura_num)
            ->first();

        return view('welcome', compact('pedido'));
    }
}
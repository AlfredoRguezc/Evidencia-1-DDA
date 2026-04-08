<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('factura_num')->unique(); //
            $table->string('numero_cliente_unico');
            $table->string('nombre_cliente');
            $table->text('datos_fiscales');
            $table->string('direccion_entrega');
            $table->string('estado')->default('Ordered'); // Ordered, In process, In route, Delivered
            $table->text('notas_extra')->nullable();
            $table->foreignId('user_id_registro')->constrained('users'); // FK al creador
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvicontrolFoodConversionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_food_conversion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('galpon_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('periodo_tipo', ['diario', 'semanal', 'mensual', 'acumulado']);
            $table->decimal('total_alimento_consumido', 10, 2);
            $table->decimal('total_producto_obtenido', 10, 2);
            $table->decimal('conversion_alimenticia', 10, 4);
            $table->enum('tipo_produccion', ['huevo', 'carne']);
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['active', 'cancelled'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('galpon_id');
            $table->index('fecha_inicio');
            $table->index('fecha_fin');
            $table->index('periodo_tipo');
            $table->index('tipo_produccion');
            $table->index('estado');

            // Claves foráneas
            $table->foreign('galpon_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avicontrol_food_conversion');
    }
}

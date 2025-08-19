<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvicontrolFoodConsumptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_food_consumption', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_registro');
            $table->unsignedBigInteger('galpon_id');
            $table->unsignedBigInteger('producto_id');
            $table->decimal('cantidad_kg', 10, 2)->nullable();
            $table->integer('cantidad_bultos')->nullable();
            $table->decimal('peso_por_bulto', 10, 2)->nullable();
            $table->decimal('consumo_promedio_por_ave', 10, 4)->nullable();
            $table->integer('numero_aves');
            $table->text('observaciones')->nullable();
            $table->string('responsable');
            $table->enum('estado', ['active', 'cancelled', 'pending'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('fecha_registro');
            $table->index('galpon_id');
            $table->index('producto_id');
            $table->index('estado');

            // Claves foráneas
            $table->foreign('galpon_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('avicontrol_inventory_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avicontrol_food_consumption');
    }
}

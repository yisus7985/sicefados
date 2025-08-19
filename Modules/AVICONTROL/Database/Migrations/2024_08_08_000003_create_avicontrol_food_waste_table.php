<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvicontrolFoodWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_food_waste', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_registro');
            $table->unsignedBigInteger('galpon_id')->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->decimal('cantidad_perdida', 10, 2);
            $table->enum('causa_merma', [
                'derrame',
                'contaminacion',
                'roedores',
                'empaque_roto',
                'humedad',
                'plagas',
                'vencimiento',
                'manejo_incorrecto',
                'otros'
            ]);
            $table->string('responsable');
            $table->text('observaciones')->nullable();
            $table->decimal('costo_perdida', 10, 2)->nullable();
            $table->enum('estado', ['active', 'cancelled', 'pending'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('fecha_registro');
            $table->index('galpon_id');
            $table->index('producto_id');
            $table->index('causa_merma');
            $table->index('estado');

            // Claves foráneas
            $table->foreign('galpon_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('set null');
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
        Schema::dropIfExists('avicontrol_food_waste');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolBirdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_birds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poultry_facility_id'); // Relación con el galpón
            $table->string('batch_code', 50)->unique(); // Código del lote
            $table->enum('bird_type', ['laying_hens', 'broilers', 'chicks', 'breeders']); // Tipo de ave
            $table->integer('quantity'); // Cantidad de aves
            $table->integer('initial_quantity'); // Cantidad inicial (para control)
            $table->date('entry_date'); // Fecha de ingreso
            $table->integer('age_weeks')->nullable(); // Edad en semanas
            $table->string('breed', 100)->nullable(); // Raza
            $table->decimal('average_weight', 8, 2)->nullable(); // Peso promedio en kg
            $table->enum('status', ['active', 'sold', 'deceased', 'transferred'])->default('active');
            $table->text('notes')->nullable(); // Notas adicionales
            $table->decimal('purchase_price', 10, 2)->nullable(); // Precio de compra por ave
            $table->string('supplier', 200)->nullable(); // Proveedor
            $table->timestamps();
            $table->softDeletes();
            
            // Índices y relaciones
            $table->foreign('poultry_facility_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('cascade');
            $table->index(['poultry_facility_id', 'bird_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avicontrol_birds');
    }
}

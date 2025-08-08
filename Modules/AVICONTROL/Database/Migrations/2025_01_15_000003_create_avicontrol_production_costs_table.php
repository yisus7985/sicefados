<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolProductionCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_production_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poultry_facility_id');
            $table->unsignedBigInteger('bird_id')->nullable();
            $table->string('batch_code', 50)->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('cost_type', ['batch', 'period']);
            $table->decimal('concentrate_cost', 12, 2)->default(0);
            $table->decimal('water_cost', 12, 2)->default(0);
            $table->decimal('energy_cost', 12, 2)->default(0);
            $table->decimal('medication_cost', 12, 2)->default(0);
            $table->decimal('biological_cost', 12, 2)->default(0);
            $table->decimal('packaging_cost', 12, 2)->default(0);
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('maintenance_cost', 12, 2)->default(0);
            $table->decimal('other_costs', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->enum('unit_type', ['egg', 'kg_meat', 'bird'])->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices y relaciones
            $table->foreign('poultry_facility_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('cascade');
            $table->foreign('bird_id')->references('id')->on('avicontrol_birds')->onDelete('set null');
            $table->index(['poultry_facility_id', 'cost_type']);
            $table->index(['batch_code', 'period_start', 'period_end']);
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
        Schema::dropIfExists('avicontrol_production_costs');
    }
} 
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolCostComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_cost_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_cost_id');
            $table->enum('component_type', [
                'concentrate',
                'water',
                'energy',
                'medication',
                'biological',
                'packaging',
                'labor',
                'maintenance',
                'other'
            ]);
            $table->string('component_name', 200);
            $table->decimal('quantity', 10, 4)->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->enum('unit_measure', [
                'kg',
                'l',
                'kwh',
                'units',
                'hours',
                'days',
                'pieces'
            ])->default('units');
            $table->text('description')->nullable();
            $table->date('date_applied');
            $table->string('supplier', 200)->nullable();
            $table->string('invoice_number', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices y relaciones
            $table->foreign('production_cost_id')->references('id')->on('avicontrol_production_costs')->onDelete('cascade');
            $table->index(['production_cost_id', 'component_type']);
            $table->index('date_applied');
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avicontrol_cost_components');
    }
} 
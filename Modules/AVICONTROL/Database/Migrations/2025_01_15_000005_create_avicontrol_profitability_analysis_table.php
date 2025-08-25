<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolProfitabilityAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_profitability_analysis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_cost_id');
            $table->enum('analysis_period', [
                'daily',
                'weekly',
                'monthly',
                'batch',
                'custom'
            ]);
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_production_cost', 12, 2)->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('other_income', 12, 2)->default(0);
            $table->decimal('other_expenses', 12, 2)->default(0);
            $table->decimal('gross_profit', 12, 2)->default(0);
            $table->decimal('gross_margin_percentage', 5, 2)->default(0);
            $table->decimal('net_profit', 12, 2)->default(0);
            $table->decimal('net_margin_percentage', 5, 2)->default(0);
            $table->decimal('profit_per_unit', 10, 2)->default(0);
            $table->enum('unit_type', ['egg', 'kg_meat', 'bird'])->nullable();
            $table->decimal('total_units_produced', 10, 4)->default(0);
            $table->decimal('average_selling_price', 10, 2)->default(0);
            $table->date('analysis_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices y relaciones
            $table->foreign('production_cost_id')->references('id')->on('avicontrol_production_costs')->onDelete('cascade');
            $table->index(['production_cost_id', 'analysis_period'], 'profit_analysis_prod_period_idx');
            $table->index(['period_start', 'period_end'], 'profit_analysis_period_idx');
            $table->index('status');
            $table->index('net_profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avicontrol_profitability_analysis');
    }
} 
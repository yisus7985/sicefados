<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolInventoryMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('avicontrol_inventory_products')->onDelete('cascade');
            $table->enum('movement_type', ['entry', 'exit', 'adjustment']);
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_value', 12, 2);
            $table->enum('reference', [
                'purchase',
                'production', 
                'consumption',
                'sale',
                'discard',
                'adjustment'
            ]);
            $table->text('notes')->nullable();
            $table->datetime('movement_date');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avicontrol_inventory_movements');
    }
} 
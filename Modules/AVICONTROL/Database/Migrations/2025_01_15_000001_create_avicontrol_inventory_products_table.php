<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolInventoryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_inventory_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 20)->unique();
            $table->enum('category', [
                'alimentos',
                'medicamentos', 
                'biologicos',
                'desinfectantes',
                'embalajes',
                'equipos',
                'otros'
            ]);
            $table->text('description')->nullable();
            $table->string('unit_measure', 50);
            $table->decimal('unit_price', 10, 2);
            $table->string('supplier', 255)->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('minimum_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
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
        Schema::dropIfExists('avicontrol_inventory_products');
    }
} 
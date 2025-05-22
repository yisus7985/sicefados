<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvicontrolPoultryManagementFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avicontrol_poultry_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('length', 8, 2)->comment('Length in meters');
            $table->decimal('width', 8, 2)->comment('Width in meters');
            $table->decimal('height', 8, 2)->comment('Height in meters');
            $table->integer('capacity')->comment('Maximum number of birds');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->date('creation_date');
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
        Schema::dropIfExists('avicontrol_poultry_facilities');
    }
}
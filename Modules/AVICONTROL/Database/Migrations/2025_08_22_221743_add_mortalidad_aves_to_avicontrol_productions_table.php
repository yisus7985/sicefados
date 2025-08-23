<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMortalidadAvesToAvicontrolProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            $table->integer('mortalidad_aves')->default(0)->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            $table->dropColumn('mortalidad_aves');
        });
    }
}

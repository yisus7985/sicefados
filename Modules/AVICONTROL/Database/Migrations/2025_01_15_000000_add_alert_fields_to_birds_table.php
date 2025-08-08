<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlertFieldsToBirdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avicontrol_birds', function (Blueprint $table) {
            $table->decimal('mortality_rate', 5, 2)->default(0.00)->after('status'); // Tasa de mortalidad (0-100%)
            $table->decimal('feed_consumption', 8, 2)->nullable()->after('mortality_rate'); // Consumo de alimento g/ave/día
            $table->string('batch_name', 100)->nullable()->after('batch_code'); // Nombre del lote
            $table->decimal('laying_rate', 5, 2)->nullable()->after('feed_consumption'); // Tasa de postura actual (%)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avicontrol_birds', function (Blueprint $table) {
            $table->dropColumn(['mortality_rate', 'feed_consumption', 'batch_name', 'laying_rate']);
        });
    }
} 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->table('checkbox_states', function (Blueprint $table) {
            $table->boolean('matriz')->default(false);
            $table->boolean('curvado')->default(false);
            $table->boolean('laminado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite')->table('checkbox_states', function (Blueprint $table) {
            $table->dropColumn('matriz');
            $table->dropColumn('curvado');
            $table->dropColumn('laminado');
        });
    }
};

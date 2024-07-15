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
            $table->boolean('disabled_matriz')->default(false);
            $table->boolean('disabled_corte')->default(false);
            $table->boolean('disabled_pulido')->default(false);
            $table->boolean('disabled_perforado')->default(false);
            $table->boolean('disabled_curvado')->default(false);
            $table->boolean('disabled_pintado')->default(false);
            $table->boolean('disabled_empavonado')->default(false);
            $table->boolean('disabled_laminado')->default(false);
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
            $table->dropColumn([
                'disabled_matriz',
                'disabled_corte',
                'disabled_pulido',
                'disabled_perforado',
                'disabled_curvado',
                'disabled_pintado',                
                'disabled_empavonado',
                'disabled_laminado'
            ]);
        });
    }
};

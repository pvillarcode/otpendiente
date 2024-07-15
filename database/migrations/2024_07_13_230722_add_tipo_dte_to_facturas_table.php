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
        Schema::connection('sqlite')->table('facturas', function (Blueprint $table) {
            $table->integer('tipo_dte')->default(33)->after('codigo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite')->table('facturas', function (Blueprint $table) {
            $table->dropColumn('tipo_dte');
        });
    }
};

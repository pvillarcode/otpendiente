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
        Schema::connection('sqlite')->create('checkbox_states', function (Blueprint $table) {
            $table->string('codigo', 20)->primary(); // Define codigo como clave primaria
            $table->boolean('corte')->default(false);
            $table->boolean('pulido')->default(false);
            $table->boolean('perforado')->default(false);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite')->dropIfExists('checkbox_states');
    }
};

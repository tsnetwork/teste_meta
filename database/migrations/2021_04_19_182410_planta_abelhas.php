<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlantaAbelhas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planta_abelhas', function(Blueprint $table){
            $table->foreignId('id_abelha')
                ->constrained('abelhas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('id_planta')
                ->constrained('plantas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planta_abelhas', function (Blueprint $table) {
            $table->dropForeign(['id_abelha']);
            $table->dropForeign(['id_planta']);                
        });

        Schema::drop('planta_abelhas');
    }
}

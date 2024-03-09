<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tc_drivers', function (Blueprint $table) {
            $table->string('telefone');
            $table->integer('cnh');
            $table->integer('cpf');
        });
    }

    public function down()
    {
        //Removendo relacionamento
        Schema::table('tc_drivers', function (Blueprint $table) {
            $table->dropColumn(['telefone', 'cnh', 'cpf']);
        });
    }
};

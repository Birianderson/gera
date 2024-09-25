<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loteamento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cidade_id')->nullable();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cidade_id')->references('id')->on('cidade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('loteamento');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estado_id')->nullable();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('estado_id')->references('id')->on('estado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cidade');
    }
};

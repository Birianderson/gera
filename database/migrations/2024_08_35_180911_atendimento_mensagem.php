<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('atendimento_mensagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('atendimento_id')->nullable();
            $table->unsignedInteger('usuario_id')->nullable();
            $table->string('texto');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('atendimento_id')->references('id')->on('atendimento');
            $table->foreign('usuario_id')->references('id')->on('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('atendimento_mensagem');
    }
};

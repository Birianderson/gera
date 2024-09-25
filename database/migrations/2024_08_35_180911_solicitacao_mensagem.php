<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solicitacao_mensagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitacao_id')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('texto');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('solicitacao_id')->references('id')->on('solicitacao');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitacao_mensagem');
    }
};

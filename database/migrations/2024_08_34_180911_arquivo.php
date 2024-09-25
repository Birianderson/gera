<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arquivo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_arquivo_id')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('tabela');
            $table->string('chave');
            $table->string('titulo');
            $table->string('descricao')->nullable();
            $table->string('nome');
            $table->string('tamanho');
            $table->string('content_type');
            $table->string('hash');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tipo_arquivo_id')->references('id')->on('tipo_arquivo');
            $table->foreign('usuario_id')->references('id')->on('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('arquivo');
    }
};

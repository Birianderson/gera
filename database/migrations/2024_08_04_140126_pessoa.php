<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up()
    {
        Schema::create('pessoa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conjuge_id')->nullable();
            $table->string('nome');
            $table->string('profissao')->nullable();
            $table->char('estado_civil',1)->nullable();
            $table->string('regime_casamento')->nullable();
            $table->char('uniao_estavel',1)->nullable();
            $table->char('status',1)->nullable();
            $table->string('nome_pai')->nullable();
            $table->string('nome_mae')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cpf');
            $table->float('renda')->nullable();
            $table->string('registro_geral')->nullable();
            $table->string('orgao_expedidor')->nullable();
            $table->string('nacionalidade')->nullable();
            $table->char('falecida',1)->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('conjuge_id')->references('id')->on('pessoa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pessoa');
    }
};

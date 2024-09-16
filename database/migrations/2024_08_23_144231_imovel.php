<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('imovel', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pessoa_id')->nullable();
            $table->unsignedInteger('loteamento_id')->nullable();
            $table->string('data_escritura')->nullable();
            $table->string('matricula_reurb')->nullable();
            $table->string('inscricao_imobiliaria')->nullable();
            $table->string('casa')->nullable();
            $table->string('quadra');
            $table->string('lote');
            $table->string('medida_frente');
            $table->string('medida_fundo');
            $table->string('medida_direita');
            $table->string('medida_esquerda');
            $table->string('area');
            $table->string('perimetro');
            $table->string('confinante_frente');
            $table->string('confinante_fundo');
            $table->string('confinante_direita');
            $table->string('confinante_esquerda');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pessoa_id')->references('id')->on('pessoa');
            $table->foreign('loteamento_id')->references('id')->on('loteamento');
        });
    }

    public function down()
    {
        Schema::dropIfExists('imovel');
    }
};

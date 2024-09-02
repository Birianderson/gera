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
            $table->foreignId('pessoa_id')->nullable();
            $table->dateTime('data_escritura')->nullable();
            $table->string('loteamento')->nullable();
            $table->string('municipio')->nullable();
            $table->string('matricula_reurb')->nullable();
            $table->string('inscricao_imobiliaria')->nullable();
            $table->string('casa')->nullable();
            $table->string('quadra')->nullable();
            $table->string('lote')->nullable();
            $table->string('medida_frente')->nullable();
            $table->string('medida_fundo')->nullable();
            $table->string('medida_lado_direito')->nullable();
            $table->string('medida_lado_esquerdo')->nullable();
            $table->string('area')->nullable();
            $table->string('area_construida')->nullable();
            $table->string('perimetro')->nullable();
            $table->float('area_numerico')->nullable();
            $table->string('confinante_frente')->nullable();
            $table->string('confinante_fundo')->nullable();
            $table->string('confinante_lado_direito')->nullable();
            $table->string('confinante_lado_esquerdo')->nullable();
            $table->float('valor_venal')->nullable();
            $table->float('valor_terreno')->nullable();
            $table->float('valor_construcao')->nullable();
            $table->string('numero_processo_administrativo')->nullable();
            $table->string('prefixo_titulo')->nullable();
            $table->string('ano_titulo')->nullable();
            $table->string('numero_titulo')->nullable();
            $table->timestamps();
            $table->softDeletes();


        });
    }

    public function down()
    {
        Schema::dropIfExists('imovel');
    }
};

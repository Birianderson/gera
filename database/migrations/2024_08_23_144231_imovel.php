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
            $table->dateTime('data_escritura')->nullable();
            $table->string('loteamento')->nullable();
            $table->string('matricula_reurb')->nullable();
            $table->string('quadra')->nullable();
            $table->string('lote')->nullable();
            $table->float('medida_frente')->nullable();
            $table->float('medida_fundo')->nullable();
            $table->float('medida_lado_direito')->nullable();
            $table->float('medida_lado_esquerdo')->nullable();
            $table->float('area_m2')->nullable();
            $table->float('perimetro')->nullable();
            $table->float('area_numerico')->nullable();
            $table->string('confinante_frente')->nullable();
            $table->string('confinante_fundo')->nullable();
            $table->string('confinante_lado_direito')->nullable();
            $table->string('confinante_lado_esquerdo')->nullable();
            $table->decimal('valor_venal', 15, 2)->nullable();
            $table->text('valor_venal_extenso')->nullable();
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
        Schema::dropIfExists('imoveis');
    }
};

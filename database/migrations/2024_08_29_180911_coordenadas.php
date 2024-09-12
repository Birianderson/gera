<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coordenadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imovel_id')->nullable();
            $table->string('municipio');
            $table->string('loteamento')->nullable();
            $table->text('lat');
            $table->text('long');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('imovel_id')->references('id')->on('imovel');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coordenadas');
    }
};

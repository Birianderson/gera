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
            $table->unsignedBigInteger('imovel_id')->nullable();
            $table->unsignedBigInteger('loteamento_id');
            $table->text('lat');
            $table->text('long');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('imovel_id')->references('id')->on('imovel');
            $table->foreign('loteamento_id')->references('id')->on('loteamento');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coordenadas');
    }
};

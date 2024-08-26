<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vinculacao_imovel_pessoas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imovel_id');
            $table->foreignId('pessoa_id');
            $table->dateTime('data_vinculacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('imovel_id')->references('id')->on('imovel');
            $table->foreign('pessoa_id')->references('id')->on('pessoa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vinculacao_imovel_pessoas');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('atendimento', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('imovel_id')->nullable();
            $table->unsignedInteger('usuario_id')->nullable();
            $table->char('status',1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('imovel_id')->references('id')->on('imovel');
            $table->foreign('usuario_id')->references('id')->on('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('atendimento');
    }
};

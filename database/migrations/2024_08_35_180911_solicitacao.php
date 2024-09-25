<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solicitacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imovel_id')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->char('status',1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('imovel_id')->references('id')->on('imovel');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitacao');
    }
};

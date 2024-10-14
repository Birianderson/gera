<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('helpdesk_mensagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('helpdesk_id');
            $table->unsignedBigInteger('usuario_id');
            $table->text('mensagem');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('helpdesk_id')->references('id')->on('helpdesk');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('helpdesk_mensagem');
    }
};

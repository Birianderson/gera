<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('usuario_credencial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('loteamento_id');
            $table->string('credencial');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('loteamento_id')->references('id')->on('loteamentos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario_credencial');
    }
};

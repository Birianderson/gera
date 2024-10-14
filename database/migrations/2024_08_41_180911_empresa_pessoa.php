<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('empresa_pessoa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('pessoa_id');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('empresa_id')->references('id')->on('empresa');
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresa_pessoa');
    }
};

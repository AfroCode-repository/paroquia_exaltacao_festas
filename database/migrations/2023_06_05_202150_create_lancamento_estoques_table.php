<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lancamento_estoque', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produto_estoque');
            $table->unsignedBigInteger('id_setor');
            $table->unsignedBigInteger('id_tipo_lancamento')->comment('id_tipo_lancamento_estoque');
            $table->integer('qtd');
            $table->text('obs')->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(1);

            $table->foreign('id_produto_estoque')->references('id')->on('produto_estoque');
            $table->foreign('id_setor')->references('id')->on('setores');
            $table->foreign('id_tipo_lancamento')->references('id')->on('tipo_lancamento_estoque');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamento_estoque', function (Blueprint $table) {
            $table->dropForeign('lancamento_estoque_id_produto_estoque_foreign');
        });

        Schema::table('lancamento_estoque', function (Blueprint $table) {
            $table->dropForeign('lancamento_estoque_id_setor_foreign');
        });

        Schema::table('lancamento_estoque', function (Blueprint $table) {
            $table->dropForeign('lancamento_estoque_id_tipo_lancamento_estoque_foreign');
        });

        Schema::dropIfExists('lancamento_estoque');
    }
};



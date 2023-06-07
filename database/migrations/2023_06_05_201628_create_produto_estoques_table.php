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
        Schema::create('produto_estoque', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_produto');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->decimal('price', $precision = 10, $scale = 2);
            $table->integer('qtd_estoque_atual');
            $table->timestamps();
            $table->tinyInteger('status')->default(1);

            $table->foreign('id_tipo_produto')->references('id')->on('tipo_produtos');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_estoque', function (Blueprint $table) {
            $table->dropForeign('produto_estoque_id_tipo_produto_foreign');
        });

        Schema::dropIfExists('produto_estoque');
    }
};

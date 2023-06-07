<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_status', function (Blueprint $table) {
            $table->id();
            $table->string('tabela');
            $table->string('description');
            $table->integer('code_status');
            $table->timestamps();
            $table->integer('status')->default(1);

            $table->index('code_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_status');
    }
};

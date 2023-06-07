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
        Schema::create('sys_log_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_tabela');
            $table->string('table_name');
            $table->string('field');
            $table->string('new_value');
            $table->string('old_value');
            $table->string('action');
            $table->string('obs')->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(1);

            $table->index(['id_user', 'id_tabela']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_log_updates');
    }
};

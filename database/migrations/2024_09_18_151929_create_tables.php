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
        Schema::create('users', function (Blueprint $table) {
            $table->string('chat_id')->primary();
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('language')->nullable();
            $table->string('destination')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->boolean('is_anticor')->nullable();
            $table->boolean('is_murojaat')->nullable();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('type');
            $table->integer('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
};

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
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('chat_id')->primary()->unique();
            $table->string('first_name')->nullable();
            $table->string('username')->nullable();
            $table->string('last_name')->nullable();
            $table->string('language')->nullable();
            $table->string('destination')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->boolean('is_anticor')->nullable();
            $table->boolean('is_murojaat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

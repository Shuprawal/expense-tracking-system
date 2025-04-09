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
        Schema::create('category_user', function (Blueprint $table) {
           $table->foreignId('user_id')->constrained('users');
           $table->foreignId('category_id')->constrained('categories');
           $table->datetime('date');
           $table->string('percentage')->nullable();

           $table->primary(['user_id', 'category_id','date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_user');
    }
};

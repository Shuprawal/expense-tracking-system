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
        Schema::table('category_user', function (Blueprint $table) {
            $table->decimal('percentage', 10, 2)->default(0)->after('category_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_user', function (Blueprint $table) {
            $table->dropColumn('percentage');
        });
    }
};

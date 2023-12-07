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
        Schema::create('usman_branch', function (Blueprint $table) {
            $table->string('branch_code')->primary();
            $table->string('branch_name');
            $table->string('kanwil_code');
            $table->string('kanwil_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usman_branch');
    }
};

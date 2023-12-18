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
        Schema::create('deployment_server_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10);
            $table->unsignedBigInteger('module_id');
            $table->boolean('is_active')->default(true);

            $table->foreign('module_id')->references('id')->on('deployment_modules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployment_server_types');
    }
};

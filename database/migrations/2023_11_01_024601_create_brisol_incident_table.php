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
        Schema::create('brisol_incident', function (Blueprint $table) {
            $table->string('inc_id')->primary();
            $table->date('reported_date')->nullable();
            $table->date('resolved_date')->nullable();
            $table->string('region')->nullable();
            $table->string('service_ci');
            $table->string('reported_source');
            $table->string('prd_tier1');
            $table->string('prd_tier2');
            $table->string('prd_tier3');
            $table->string('ctg_tier1');
            $table->string('ctg_tier2');
            $table->string('ctg_tier3');
            $table->string('resolution_category');
            $table->string('priority');
            $table->string('status');
            $table->string('slm_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brisol_incident');
    }
};

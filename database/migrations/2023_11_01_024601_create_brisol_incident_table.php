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
            $table->string('name');
            $table->string('region')->nullable();
            $table->string('site_group');
            $table->string('site');
            $table->string('description');
            $table->text('detailed_description')->nullable();
            $table->string('service_ci');
            $table->string('prd_tier1');
            $table->string('prd_tier2');
            $table->string('prd_tier3');
            $table->string('ctg_tier1');
            $table->string('ctg_tier2');
            $table->string('ctg_tier3');
            $table->string('resolution_category');
            $table->text('resolution')->nullable();
            $table->date('responded_date')->nullable();
            $table->string('reported_source');
            $table->string('assigned_group');
            $table->string('assignee');
            $table->string('priority');
            $table->string('urgency');
            $table->string('impact');
            $table->string('status');
            $table->string('slm_status');
            $table->date('resolved_date')->nullable();
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

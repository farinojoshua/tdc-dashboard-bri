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
        Schema::create('usman_incident', function (Blueprint $table) {
            $table->id();
            $table->date('reported_date');
            $table->string('req_type');
            $table->string('branch_code');
            $table->foreign('branch_code')->references('branch_code')->on('usman_branch');
            $table->string('req_status')->nullable();
            $table->string('exec_status')->default('Pending');
            $table->date('execution_date')->nullable();
            $table->enum('sla_category', ['Meet SLA', 'Over SLA'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usman_incident');
    }
};

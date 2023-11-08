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
        Schema::create('bjm_background_jobs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Product', 'Non-Product']);
            $table->foreignId('process_id')->constrained('bjm_processes');
            $table->integer('data_amount_to_EIM');
            $table->integer('data_amount_to_S4GL');
            $table->enum('status', ['Normal Run', 'Rerun Background Job', 'Manual Run Background Job', 'Pending']);
            $table->integer('duration_to_EIM');
            $table->integer('duration_to_S4GL');
            $table->string('notes')->nullable();
            $table->date('execution_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bjm_background_jobs');
    }
};

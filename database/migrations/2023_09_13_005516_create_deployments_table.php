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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('server_type_id');
            $table->date('deploy_date');
            $table->enum('document_status', ['Done', 'Not Done', 'In Progress']);
            $table->text('document_description');
            $table->enum('cm_status', ['Draft', 'Reviewer', 'Checker', 'Signer', 'Done deploy']);
            $table->text('cm_description');

            $table->foreign('module_id')->references('id')->on('deployment_modules')->onDelete('cascade');
            $table->foreign('server_type_id')->references('id')->on('deployment_server_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};

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
        Schema::create('archivelendings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('archive_id')->constrained();
            $table->string('officer_name')->nullable();
            $table->string('officer_position')->nullable();
            $table->string('applicant_name');
            $table->string('applicant_position');
            $table->string('applicant_organization');
            $table->string('applicant_address');
            $table->string('applicant_phone');
            $table->string('applicant_email');
            $table->string('applicant_id_number');
            $table->date('date_of_application');
            $table->date('date_of_approval')->nullable();
            $table->date('date_of_lending');
            $table->date('lending_until');
            $table->text('applicant_note')->nullable();
            $table->text('officer_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivelendings');
    }
};

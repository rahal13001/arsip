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
        Schema::table('archivelendings', function (Blueprint $table) {
            $table->string('return_officer_name')->nullable();
            $table->string('returner_name')->nullable();
            $table->string('returner_phone')->nullable();
            $table->string('return_date')->nullable();
            $table->string('return_note')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivelendings', function (Blueprint $table) {
            //
        });
    }
};

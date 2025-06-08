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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('filecode_id')->constrained();
            $table->foreignId('archivetype_id')->constrained();
            $table->foreignId('accessclassification_id')->constrained();
            $table->string('archive_name');
            $table->string('archive_slug')->unique();
            $table->string('archive_number');
            $table->date('date_make')->nullable();
            $table->date('date_input');
            $table->text('archive_description');
            $table->integer('sheet_number', false);
            $table->string('storage_location');
            $table->string('development');
            $table->string('document')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};

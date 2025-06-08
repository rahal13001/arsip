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
        Schema::create('archiveusers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->constrained();
            $table->date('active_date')->nullable();
            $table->date('inactive_date')->nullable();
            $table->integer('active_save_time', false)->nullable();
            $table->integer('inactive_save_time', false)->nullable();
            $table->string('archive_properties')->nullable();
            $table->boolean('archive_status')->nullable();
            $table->date('destruction_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archiveusers');
    }
};

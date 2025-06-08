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
        Schema::create('archivereturns', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('archivelending_id')->constrained();
            $table->string('return_officer_name');
            $table->string('return_officer_position');
            $table->string('return_name');
            $table->string('return_position');
            $table->string('return_organization');
            $table->string('return_address');
            $table->string('return_phone');
            $table->string('return_email');
            $table->string('return_id_number');
            $table->date('return_date');
            $table->text('return_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivereturns');
    }
};

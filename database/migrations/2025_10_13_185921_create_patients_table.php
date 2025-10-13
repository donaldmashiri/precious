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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('patient_number')->unique();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_number')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->boolean('is_chronic_patient')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

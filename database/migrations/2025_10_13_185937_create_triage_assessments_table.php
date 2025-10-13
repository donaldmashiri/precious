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
        Schema::create('triage_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable();
            $table->foreignId('assessed_by'); // Nurse/Staff who did triage
            $table->enum('urgency_level', ['critical', 'urgent', 'semi_urgent', 'standard', 'non_urgent']);
            $table->integer('priority_score'); // 1-100 scale
            $table->text('chief_complaint');
            $table->json('vital_signs')->nullable(); // BP, pulse, temp, etc.
            $table->text('pain_scale')->nullable(); // 1-10
            $table->text('symptoms_description');
            $table->text('medical_history_notes')->nullable();
            $table->boolean('requires_immediate_attention')->default(false);
            $table->string('recommended_department')->nullable();
            $table->text('triage_notes')->nullable();
            $table->dateTime('assessed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage_assessments');
    }
};

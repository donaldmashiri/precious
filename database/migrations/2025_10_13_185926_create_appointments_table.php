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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            $table->foreignId('patient_id');
            $table->foreignId('doctor_id');
            $table->foreignId('hospital_id');
            $table->foreignId('department_id');
            $table->dateTime('appointment_date');
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->enum('type', ['consultation', 'follow_up', 'emergency', 'routine_checkup']);
            $table->text('reason_for_visit')->nullable();
            $table->text('symptoms')->nullable();
            $table->integer('priority_score')->default(0); // Calculated from triage
            $table->text('notes')->nullable();
            $table->decimal('consultation_fee', 8, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->dateTime('checked_in_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

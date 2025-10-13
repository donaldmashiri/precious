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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('specialization');
            $table->text('qualifications')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->json('available_days')->nullable(); // ['monday', 'tuesday', etc.]
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->integer('max_patients_per_day')->default(20);
            $table->decimal('consultation_fee', 8, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};

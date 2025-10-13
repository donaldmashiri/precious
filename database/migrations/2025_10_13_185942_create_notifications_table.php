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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['appointment', 'triage', 'system', 'reminder', 'emergency']);
            $table->json('data')->nullable(); // Additional data like appointment_id, etc.
            $table->boolean('is_read')->default(false);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->dateTime('scheduled_for')->nullable(); // For scheduled notifications
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

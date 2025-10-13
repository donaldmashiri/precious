<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TriageAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'assessed_by',
        'urgency_level',
        'priority_score',
        'chief_complaint',
        'vital_signs',
        'pain_scale',
        'symptoms_description',
        'medical_history_notes',
        'requires_immediate_attention',
        'recommended_department',
        'triage_notes',
        'assessed_at',
    ];

    protected function casts(): array
    {
        return [
            'vital_signs' => 'array',
            'requires_immediate_attention' => 'boolean',
            'assessed_at' => 'datetime',
        ];
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    // Helper methods
    public function getUrgencyColorAttribute()
    {
        return match($this->urgency_level) {
            'critical' => 'red',
            'urgent' => 'orange',
            'semi_urgent' => 'yellow',
            'standard' => 'blue',
            'non_urgent' => 'green',
            default => 'gray'
        };
    }

    public static function calculatePriorityScore($urgencyLevel, $vitalSigns, $painScale, $requiresImmediateAttention)
    {
        $baseScore = match($urgencyLevel) {
            'critical' => 90,
            'urgent' => 70,
            'semi_urgent' => 50,
            'standard' => 30,
            'non_urgent' => 10,
            default => 0
        };

        // Add points for pain scale
        if ($painScale && $painScale >= 7) {
            $baseScore += 15;
        } elseif ($painScale && $painScale >= 5) {
            $baseScore += 10;
        }

        // Add points for immediate attention
        if ($requiresImmediateAttention) {
            $baseScore += 20;
        }

        return min(100, $baseScore);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'patient_id',
        'doctor_id',
        'hospital_id',
        'department_id',
        'appointment_date',
        'status',
        'type',
        'reason_for_visit',
        'symptoms',
        'priority_score',
        'notes',
        'consultation_fee',
        'is_paid',
        'checked_in_at',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'datetime',
            'consultation_fee' => 'decimal:2',
            'is_paid' => 'boolean',
            'checked_in_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appointment) {
            if (!$appointment->appointment_number) {
                $appointment->appointment_number = 'APT-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function triageAssessment()
    {
        return $this->hasOne(TriageAssessment::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

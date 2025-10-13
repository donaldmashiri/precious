<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_number',
        'blood_type',
        'height',
        'weight',
        'medical_history',
        'allergies',
        'current_medications',
        'insurance_provider',
        'insurance_number',
        'insurance_expiry',
        'is_chronic_patient',
    ];

    protected function casts(): array
    {
        return [
            'height' => 'decimal:2',
            'weight' => 'decimal:2',
            'insurance_expiry' => 'date',
            'is_chronic_patient' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function triageAssessments()
    {
        return $this->hasMany(TriageAssessment::class);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getBmiAttribute()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return null;
    }
}

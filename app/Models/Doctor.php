<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'specialization',
        'qualifications',
        'years_of_experience',
        'department_id',
        'available_days',
        'shift_start',
        'shift_end',
        'max_patients_per_day',
        'consultation_fee',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'available_days' => 'array',
            'shift_start' => 'datetime:H:i',
            'shift_end' => 'datetime:H:i',
            'consultation_fee' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function isAvailableOnDay($day)
    {
        return in_array(strtolower($day), array_map('strtolower', $this->available_days ?? []));
    }
}

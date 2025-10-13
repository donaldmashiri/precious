<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'website',
        'type',
        'bed_capacity',
        'is_active',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctors()
    {
        return $this->hasManyThrough(Doctor::class, User::class);
    }
}

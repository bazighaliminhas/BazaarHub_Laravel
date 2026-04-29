<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'device_user_id',
        'employee_name',
        'punch_time',
        'punch_type',
        'device_ip',
    ];

    protected $casts = [
        'punch_time' => 'datetime',
        'punch_type' => 'integer',
    ];

    // ✅ Scope: Today's attendance
    public function scopeToday($query)
    {
        return $query->whereDate('punch_time', today());
    }

    // ✅ Scope: By employee
    public function scopeByEmployee($query, $id)
    {
        return $query->where('device_user_id', $id);
    }

    // ✅ Helper: punch type label
    public function getPunchLabelAttribute(): string
    {
        return $this->punch_type === 0 ? 'Check IN' : 'Check OUT';
    }

    // ✅ Helper: punch badge color
    public function getPunchColorAttribute(): string
    {
        return $this->punch_type === 0 ? 'success' : 'danger';
    }
}
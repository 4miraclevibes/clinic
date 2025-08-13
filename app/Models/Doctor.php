<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name', 'no_hp', 'alamat', 'spesialis', 'no_str', 'no_sip', 'no_spesialis'];

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}

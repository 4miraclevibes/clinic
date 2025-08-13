<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = ['patient_id', 'no_antrian', 'status', 'doctor_id', 'user_id', 'keterangan'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'queue_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'queue_id');
    }
}

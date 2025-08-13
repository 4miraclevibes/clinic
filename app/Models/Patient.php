<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'no_rekam_medis', 'nik', 'no_hp', 'alamat'];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function medicalRecords()
    {
        return $this->hasManyThrough(MedicalRecord::class, Queue::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = ['queue_id', 'doctor_id', 'user_id', 'diagnosis', 'treatment', 'keterangan'];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

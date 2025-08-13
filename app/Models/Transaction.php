<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['queue_id', 'user_id', 'status', 'keterangan', 'total_amount'];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->transactionDetails->sum('harga');
    }
}

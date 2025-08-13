<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = ['name', 'no_hp', 'alamat', 'role'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

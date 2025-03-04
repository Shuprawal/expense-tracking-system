<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forecastincome extends Model
{
    protected $fillable = ['user_id', 'amount'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}

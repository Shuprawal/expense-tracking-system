<?php

namespace App\Models;

use App\Statementable;
use Illuminate\Database\Eloquent\Model;

class Forecastincome extends Model
{
    use Statementable;
    protected $fillable = ['user_id', 'amount'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}

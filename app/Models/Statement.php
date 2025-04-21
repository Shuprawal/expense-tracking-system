<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $fillable = ['amount','date', 'statementable_id', 'statementable_type'];


    public function statementable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'type'];



    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('percentage','date')->distinct();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }


}

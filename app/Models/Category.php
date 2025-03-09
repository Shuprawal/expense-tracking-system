<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'type'];



    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('percentage','date')->distinct();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function scopeUserOrAdmin($query)
    {
        return $query->where(function ($query) {
            $query->whereHas('users', function ($subQuery) {
                $subQuery->where('users.id', auth()->id());
            })
                ->orWhereHas('users', function ($subQuery) {
                    $subQuery->where('users.role', 'admin');
            });
        });
    }

}

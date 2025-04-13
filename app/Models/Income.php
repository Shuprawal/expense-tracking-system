<?php

namespace App\Models;

use App\Trait\Statementable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory, Statementable;
    protected $fillable = ['user_id', 'amount', 'description', 'category_id','date'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }



}

<?php

namespace App\Models;

use App\Statementable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
//    public function statements()
//    {
//        return $this->morphMany(Statement::class, 'statementable' );
//    }

}

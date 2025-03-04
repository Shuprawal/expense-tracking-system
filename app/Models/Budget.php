<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['user_id', 'amount', 'limit','alert'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }



    public function increaseBudget($incomeAmount){
         $this->amount+= $incomeAmount;
         $this->save();
    }

    public function decreaseBudget($incomeAmount){
        $this->amount -= $incomeAmount;
        $this->save();
    }

}

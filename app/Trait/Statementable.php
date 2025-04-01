<?php

namespace App\Trait;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Statementable
{
  public function statements(): MorphMany
  {
      return $this->morphMany(Statement::class, 'statementable');
  }

}

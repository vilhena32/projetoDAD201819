<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Meal;

class Invoice extends Model
{

  public function meal()
  {
      return $this->belongsTo(Meal::class);
  }
}

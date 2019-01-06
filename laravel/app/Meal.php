<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Invoice;

class Meal extends Model
{
  protected $fillable = [
      'table_number',
      'state',
      'start',
      'end',
      'total_price_preview',
      'responsible_waiter_id',
  ];

  public function user(){
    return $this->belongsTo(User::class, 'responsible_waiter_id');
  }

  public function invoice(){
    return $this->belongsTo(Invoice::class);
  }
}

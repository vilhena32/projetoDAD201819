<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
      'state',
      'start',
      'item_id',
      'meal_id',
      'end'
    ];

    public function meal()
    {
        return $this->hasOne(Meal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'responsible_cook_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
